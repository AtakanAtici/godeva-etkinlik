<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Answer;
use App\Events\ParticipantJoined;
use App\Events\AnswerSubmitted;
use Livewire\Component;
use Livewire\Attributes\On;

class ParticipantJoin extends Component
{
    public string $roomCode;
    public ?Room $room = null;
    public ?Participant $participant = null;
    public ?Question $currentQuestion = null;
    public string $nickname = '';
    public string $answer_content = '';
    public bool $joined = false;
    public bool $waiting_for_question = false;
    public ?string $lastQuestionId = null;
    public bool $submitting = false;
    public ?string $errorMessage = null;

    public function mount(string $roomCode)
    {
        $this->roomCode = $roomCode;
        $this->room = Room::where('code', $roomCode)->firstOrFail();
        $this->loadParticipant();

        if ($this->joined) {
            $this->loadQuestionState();
        } else {
            $this->currentQuestion = null;
            $this->waiting_for_question = true;
            $this->lastQuestionId = null;
        }
    }

    public function loadParticipant()
    {
        $sessionId = session()->getId();
        $this->participant = $this->room->participants()
            ->where('session_id', $sessionId)
            ->first();

        $this->joined = (bool) $this->participant;
    }

    public function joinRoom()
    {
        $this->validate([
            'nickname' => 'nullable|string|max:50'
        ]);

        $sessionId = session()->getId();

        $this->participant = $this->room->participants()->updateOrCreate(
            ['session_id' => $sessionId],
            [
                'nickname' => $this->nickname ?: 'Anonim',
                'ip_address' => request()->ip(),
                'last_seen_at' => now()
            ]
        );

        if ($this->participant->wasRecentlyCreated) {
            // broadcast(new ParticipantJoined($this->participant))->toOthers(); // Temporarily disabled
        }

        $this->joined = true;
        $this->loadQuestionState();
    }

    public function loadQuestionState()
    {
        $this->currentQuestion = $this->room->activeQuestion();

        if ($this->currentQuestion) {
            $this->waiting_for_question = false;
            $this->lastQuestionId = $this->currentQuestion->id;
        } else {
            $this->waiting_for_question = true;
            $this->lastQuestionId = null;
        }
    }

    public function checkForQuestionUpdates()
    {
        // Ensure participant state is maintained
        $this->loadParticipant();

        if (!$this->joined) {
            return; // Don't check for updates if not joined
        }

        $this->room = Room::where('code', $this->roomCode)->firstOrFail();
        $newQuestion = $this->room->activeQuestion();

        $newQuestionId = $newQuestion ? $newQuestion->id : null;

        // Check if question state changed
        if ($this->lastQuestionId !== $newQuestionId) {
            $this->loadQuestionState();

            // Clear answer content if question changed
            if ($newQuestion && $this->lastQuestionId && $this->lastQuestionId !== $newQuestion->id) {
                $this->answer_content = '';
                $this->errorMessage = null; // Clear any error messages
                session()->forget('answer_submitted');
            }
        }
    }

    public function submitAnswer()
    {
        // Prevent duplicate submissions
        if ($this->submitting || !$this->currentQuestion || !$this->participant) {
            return;
        }

        $this->submitting = true;

        try {
            // Validate based on question type
            if ($this->currentQuestion->type === 'multiple_choice') {
                $this->validate([
                    'answer_content' => 'required|numeric|min:0|max:' . (count($this->currentQuestion->options) - 1)
                ]);
            } else {
                $this->validate([
                    'answer_content' => 'required|string|max:1000'
                ]);
            }

            // Check if user already submitted an answer for this question
            $existingAnswer = $this->currentQuestion->answers()
                ->where('participant_id', $this->participant->id)
                ->first();

            if ($existingAnswer) {
                $this->errorMessage = 'Bu soruya zaten cevap verdiniz!';
                $this->submitting = false;
                return;
            }

            // Prepare answer content based on question type
            $answerContent = $this->answer_content;
            if ($this->currentQuestion->type === 'multiple_choice') {
                $optionIndex = intval($this->answer_content);
                $selectedOption = $this->currentQuestion->options[$optionIndex] ?? '';
                $answerContent = chr(65 + $optionIndex) . '. ' . $selectedOption;
            }

            $answer = $this->currentQuestion->answers()->create([
                'participant_id' => $this->participant->id,
                'content' => $answerContent,
                'submitted_at' => now()
            ]);

            $answer->load('participant');
            // broadcast(new AnswerSubmitted($answer))->toOthers(); // Temporarily disabled for development

            $this->answer_content = '';
            $this->errorMessage = null; // Clear any errors on success
            session()->flash('answer_submitted', 'Cevabınız gönderildi!');
        } catch (\Exception $e) {
            $this->errorMessage = 'Cevap gönderilirken bir hata oluştu.';
        } finally {
            $this->submitting = false;
        }
    }

    #[On('echo:room.{roomCode},question.published')]
    public function onQuestionPublished($data)
    {
        $this->currentQuestion = Question::find($data['question']['id']);
        $this->waiting_for_question = false;
        $this->answer_content = '';
    }

    #[On('echo:room.{roomCode},question.closed')]
    public function onQuestionClosed($data)
    {
        $this->currentQuestion = null;
        $this->waiting_for_question = true;
        $this->answer_content = '';
    }

    public function render()
    {
        return view('livewire.participant-join');
    }
}
