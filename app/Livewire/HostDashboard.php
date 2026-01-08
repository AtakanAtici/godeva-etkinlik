<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Question;
use App\Models\Answer;
use App\Events\QuestionPublished;
use App\Events\QuestionClosed;
use Livewire\Component;
use Livewire\Attributes\On;

class HostDashboard extends Component
{
    public string $roomId;
    public ?Room $room = null;
    public string $new_question_title = '';
    public string $question_type = 'open_text';
    public $recent_answers = [];
    public int $total_participants = 0;
    public ?Question $current_question = null;
    public bool $editing_title = false;
    public string $room_title = '';
    public array $question_options = ['', '', '', '']; // 4 default options
    public int $correct_option = -1; // For future voting results
    
    public function mount(string $roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::findOrFail($this->roomId);
        $this->room_title = $this->room->title; // Initialize before loadRoom
        $this->loadRoom();
        $this->loadStats();
    }

    public function loadRoom()
    {
        $this->room = Room::findOrFail($this->roomId);
        $this->current_question = $this->room->activeQuestion();
        // Don't override room_title if we're editing
        if (!$this->editing_title) {
            $this->room_title = $this->room->title;
        }
    }

    public function loadStats()
    {
        $this->total_participants = $this->room->participantCount();
        
        if ($this->current_question) {
            $this->recent_answers = $this->current_question->answers()
                ->with('participant')
                ->where('is_hidden', false)
                ->orderBy('submitted_at', 'desc')
                ->take(10)
                ->get();
        }
    }

    public function createQuestion()
    {
        $validationRules = [
            'new_question_title' => 'required|string|max:500|min:5',
        ];

        // Validate multiple choice options if needed
        if ($this->question_type === 'multiple_choice') {
            $validationRules['question_options'] = 'required|array|min:2';
            $validationRules['question_options.*'] = 'required|string|max:200';
        }

        $this->validate($validationRules);

        $options = null;
        if ($this->question_type === 'multiple_choice') {
            // Filter out empty options and reindex
            $options = array_values(array_filter($this->question_options, fn($option) => !empty(trim($option))));
        }

        $question = $this->room->questions()->create([
            'title' => $this->new_question_title,
            'type' => $this->question_type,
            'options' => $options
        ]);

        $this->resetForm();
        session()->flash('success', 'Soru oluşturuldu!');
        $this->loadRoom();
    }

    public function resetForm()
    {
        $this->new_question_title = '';
        $this->question_type = 'open_text';
        $this->question_options = ['', '', '', ''];
        $this->correct_option = -1;
    }

    public function addOption()
    {
        if (count($this->question_options) < 6) { // Maximum 6 options
            $this->question_options[] = '';
        }
    }

    public function removeOption($index)
    {
        if (count($this->question_options) > 2) { // Minimum 2 options
            array_splice($this->question_options, $index, 1);
            $this->question_options = array_values($this->question_options); // Reindex
        }
    }

    public function publishQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);
        
        // Close current question if any
        if ($this->current_question && $this->current_question->id !== $question->id) {
            $this->current_question->close();
            // broadcast(new QuestionClosed($this->current_question))->toOthers(); // Temporarily disabled
        }

        $question->publish();
        $this->room->update(['status' => 'active']);
        
        // broadcast(new QuestionPublished($question))->toOthers(); // Temporarily disabled
        
        session()->flash('success', 'Soru yayınlandı!');
        $this->loadRoom();
        $this->loadStats();
    }

    public function closeQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);
        
        $question->close();
        // broadcast(new QuestionClosed($question))->toOthers(); // Temporarily disabled
        
        session()->flash('success', 'Soru kapatıldı!');
        $this->loadRoom();
        $this->recent_answers = [];
    }

    public function reopenQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);
        
        // Close current question if any
        if ($this->current_question && $this->current_question->id !== $question->id) {
            $this->current_question->close();
            // broadcast(new QuestionClosed($this->current_question))->toOthers(); // Temporarily disabled
        }

        $question->reopen();
        $this->room->update(['status' => 'active']);
        
        // broadcast(new QuestionPublished($question))->toOthers(); // Temporarily disabled
        
        session()->flash('success', 'Soru yeniden açıldı!');
        $this->loadRoom();
        $this->loadStats();
    }

    public function hideAnswer($answerId)
    {
        $answer = Answer::findOrFail($answerId);
        
        $answer->hide();
        session()->flash('success', 'Cevap gizlendi!');
        $this->loadStats();
    }

    public function refreshStats()
    {
        $this->loadRoom();
        $this->loadStats();
    }

    #[On('echo:room.{room.code},answer.submitted')]
    public function onAnswerSubmitted($data)
    {
        $this->loadStats();
    }

    #[On('echo:room.{room.code},participant.joined')]
    public function onParticipantJoined($data)
    {
        $this->total_participants = $data['participant_count'];
    }

    public function startEditingTitle()
    {
        $this->editing_title = true;
        $this->room_title = $this->room->title;
    }

    public function cancelEditingTitle()
    {
        $this->editing_title = false;
        $this->room_title = $this->room->title;
    }

    public function saveRoomTitle()
    {
        $this->validate([
            'room_title' => 'required|string|max:255|min:3'
        ], [
            'room_title.required' => 'Etkinlik başlığı gereklidir.',
            'room_title.max' => 'Etkinlik başlığı en fazla 255 karakter olabilir.',
            'room_title.min' => 'Etkinlik başlığı en az 3 karakter olmalıdır.'
        ]);

        $this->room->update(['title' => $this->room_title]);
        $this->editing_title = false;
        
        session()->flash('success', 'Etkinlik başlığı güncellendi!');
        $this->loadRoom();
    }

    public function render()
    {
        return view('livewire.host-dashboard', [
            'questions' => $this->room->questions()->orderBy('created_at', 'desc')->get()
        ]);
    }
}
