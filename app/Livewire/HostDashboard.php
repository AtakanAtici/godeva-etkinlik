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
    
    public function mount(string $roomId)
    {
        $this->roomId = $roomId;
        $this->loadRoom();
        $this->loadStats();
    }

    public function loadRoom()
    {
        $this->room = Room::findOrFail($this->roomId);
        $this->current_question = $this->room->activeQuestion();
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
        $this->validate([
            'new_question_title' => 'required|string|max:500|min:5',
        ]);

        $question = $this->room->questions()->create([
            'title' => $this->new_question_title,
            'type' => $this->question_type
        ]);

        $this->new_question_title = '';
        session()->flash('success', 'Soru oluşturuldu!');
        $this->loadRoom();
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

    public function render()
    {
        return view('livewire.host-dashboard', [
            'questions' => $this->room->questions()->orderBy('created_at', 'desc')->get()
        ]);
    }
}
