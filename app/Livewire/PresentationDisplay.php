<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Question;
use App\Models\Answer;
use Livewire\Component;
use Livewire\Attributes\On;

class PresentationDisplay extends Component
{
    public string $roomCode;
    public ?Room $room = null;
    public ?Question $currentQuestion = null;
    public $recentAnswers = [];
    public int $participantCount = 0;
    public $wordCloudData = [];
    public int $lastAnswerCount = 0;

    public function mount(string $roomCode)
    {
        $this->roomCode = $roomCode;
        $this->loadRoom();
        $this->loadData();
    }

    public function loadRoom()
    {
        $this->room = Room::where('code', $this->roomCode)->firstOrFail();
        $this->currentQuestion = $this->room->activeQuestion();
    }

    public function loadData()
    {
        $this->participantCount = $this->room->participantCount();

        if ($this->currentQuestion) {
            $this->recentAnswers = $this->currentQuestion->answers()
                ->with('participant')
                ->where('is_hidden', false)
                ->orderBy('submitted_at', 'desc')
                ->take(24)
                ->get();

            $this->lastAnswerCount = $this->recentAnswers->count();

            // Generate word cloud data
            $this->generateWordCloud();
        }
    }

    public function checkForUpdates()
    {
        $this->loadRoom();
        
        if ($this->currentQuestion) {
            $currentAnswerCount = $this->currentQuestion->answers()
                ->where('is_hidden', false)
                ->count();
                
            if ($currentAnswerCount !== $this->lastAnswerCount) {
                $this->loadData();
            }
        }
    }

    public function generateWordCloud()
    {
        if (!$this->currentQuestion) {
            $this->wordCloudData = [];
            return;
        }

        $allAnswers = $this->currentQuestion->answers()
            ->where('is_hidden', false)
            ->pluck('content')
            ->join(' ');

        // Simple word frequency calculation
        $words = str_word_count(strtolower($allAnswers), 1, 'çğıöşüÇĞIİÖŞÜ');
        $stopWords = ['ve', 'ile', 'bir', 'bu', 'şu', 'o', 'ben', 'sen', 'biz', 'siz', 'onlar', 'var', 'yok', 'için', 'çok', 'daha', 'en', 'de', 'da', 'ki', 'olan', 'olarak'];
        
        $wordCounts = array_count_values(array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        }));

        arsort($wordCounts);
        $this->wordCloudData = array_slice($wordCounts, 0, 15);
    }

    #[On('echo:room.{roomCode},question.published')]
    public function onQuestionPublished($data)
    {
        $this->currentQuestion = Question::find($data['question']['id']);
        $this->recentAnswers = [];
        $this->wordCloudData = [];
    }

    #[On('echo:room.{roomCode},question.closed')]
    public function onQuestionClosed($data)
    {
        $this->currentQuestion = null;
        $this->recentAnswers = [];
        $this->wordCloudData = [];
    }

    #[On('echo:room.{roomCode},answer.submitted')]
    public function onAnswerSubmitted($data)
    {
        $this->loadData();
        $this->dispatch('$refresh');
    }

    #[On('echo:room.{roomCode},participant.joined')]
    public function onParticipantJoined($data)
    {
        $this->participantCount = $data['participant_count'];
    }

    public function render()
    {
        return view('livewire.presentation-display');
    }
}
