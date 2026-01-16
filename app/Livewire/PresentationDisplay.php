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
    public $multipleChoiceResults = [];
    public bool $answersRevealed = false;
    public ?string $revealTime = null;

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
            // Check if answers should be revealed
            $this->answersRevealed = $this->currentQuestion->shouldRevealAnswers();
            $this->revealTime = $this->currentQuestion->reveal_time?->toIso8601String();

            // Only load answer data if revealed
            if ($this->answersRevealed) {
                $this->recentAnswers = $this->currentQuestion->answers()
                    ->with('participant')
                    ->where('is_hidden', false)
                    ->orderBy('submitted_at', 'desc')
                    ->take(24)
                    ->get();

                $this->lastAnswerCount = $this->recentAnswers->count();

                // Generate appropriate data based on question type
                if ($this->currentQuestion->type === 'multiple_choice') {
                    $this->generateMultipleChoiceResults();
                } else {
                    $this->generateWordCloud();
                }
            } else {
                // During delay period, don't show answers
                $this->recentAnswers = [];
                $this->wordCloudData = [];
                $this->multipleChoiceResults = [];
            }
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

    public function generateMultipleChoiceResults()
    {
        if (!$this->currentQuestion || !$this->currentQuestion->options) {
            $this->multipleChoiceResults = [];
            return;
        }

        // Initialize results array
        $results = [];
        $totalVotes = 0;

        foreach ($this->currentQuestion->options as $index => $option) {
            $results[$index] = [
                'option' => $option,
                'letter' => chr(65 + $index),
                'count' => 0,
                'percentage' => 0
            ];
        }

        // Count votes for each option
        $answers = $this->currentQuestion->answers()
            ->where('is_hidden', false)
            ->get();

        foreach ($answers as $answer) {
            $index = null;
            
            // Try to extract from formatted answer (e.g., "A. Option text")
            if (preg_match('/^([A-Z])\./', $answer->content, $matches)) {
                $letter = $matches[1];
                $index = ord($letter) - 65; // Convert A=0, B=1, etc.
            }
            // Handle raw numeric answers (e.g., "0", "1", "2")
            elseif (is_numeric($answer->content) && intval($answer->content) >= 0 && intval($answer->content) < count($this->currentQuestion->options)) {
                $index = intval($answer->content);
            }
            
            if ($index !== null && isset($results[$index])) {
                $results[$index]['count']++;
                $totalVotes++;
            }
        }

        // Calculate percentages
        if ($totalVotes > 0) {
            foreach ($results as $index => $result) {
                $results[$index]['percentage'] = round(($result['count'] / $totalVotes) * 100, 1);
            }
        }

        $this->multipleChoiceResults = array_values($results);
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
        $this->answersRevealed = false;
        $this->revealTime = $data['question']['reveal_time'] ?? null;
        $this->recentAnswers = [];
        $this->wordCloudData = [];
        $this->multipleChoiceResults = [];
    }

    #[On('echo:room.{roomCode},question.closed')]
    public function onQuestionClosed($data)
    {
        $this->currentQuestion = null;
        $this->recentAnswers = [];
        $this->wordCloudData = [];
        $this->multipleChoiceResults = [];
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

    public function checkRevealStatus()
    {
        if ($this->currentQuestion && !$this->answersRevealed) {
            if ($this->currentQuestion->shouldRevealAnswers()) {
                $this->answersRevealed = true;
                $this->loadData();
            }
        }
    }

    public function render()
    {
        return view('livewire.presentation-display');
    }
}
