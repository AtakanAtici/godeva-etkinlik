<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function roomReport($roomId)
    {
        $room = Room::with(['questions.answers.participant', 'participants'])->findOrFail($roomId);
        
        // Genel istatistikler
        $stats = [
            'total_participants' => $room->participants->count(),
            'total_questions' => $room->questions->count(),
            'total_answers' => $room->questions->sum(function ($q) { return $q->answers->count(); }),
            'avg_response_rate' => 0,
            'questions_data' => []
        ];
        
        // Her soru için detaylı analiz
        foreach ($room->questions as $question) {
            $questionData = [
                'id' => $question->id,
                'title' => $question->title,
                'type' => $question->type,
                'answer_count' => $question->answers->count(),
                'response_rate' => $stats['total_participants'] > 0 
                    ? round(($question->answers->count() / $stats['total_participants']) * 100, 1) 
                    : 0,
                'created_at' => $question->created_at,
                'published_at' => $question->published_at,
            ];
            
            if ($question->type === 'multiple_choice' && $question->options) {
                // Çoktan seçmeli soru analizi
                $results = [];
                $totalVotes = 0;
                
                foreach ($question->options as $index => $option) {
                    $results[$index] = [
                        'option' => $option,
                        'letter' => chr(65 + $index),
                        'count' => 0,
                        'percentage' => 0
                    ];
                }
                
                foreach ($question->answers as $answer) {
                    // Birden fazla seçimi handle et
                    $selections = explode(', ', $answer->content);
                    
                    foreach ($selections as $selection) {
                        if (preg_match('/^([A-Z])\./', $selection, $matches)) {
                            $letter = $matches[1];
                            $index = ord($letter) - 65;
                            
                            if (isset($results[$index])) {
                                $results[$index]['count']++;
                                $totalVotes++;
                            }
                        }
                    }
                }
                
                // Yüzdeleri hesapla
                if ($totalVotes > 0) {
                    foreach ($results as $index => $result) {
                        $results[$index]['percentage'] = round(($result['count'] / $totalVotes) * 100, 1);
                    }
                }
                
                $questionData['results'] = array_values($results);
            } else {
                // Açık uçlu soru için tüm cevapları al
                $questionData['answers'] = $question->answers->map(function ($answer) {
                    return [
                        'content' => $answer->content,
                        'participant' => $answer->participant->nickname,
                        'submitted_at' => $answer->submitted_at
                    ];
                })->toArray();
            }
            
            $stats['questions_data'][] = $questionData;
        }
        
        // Ortalama katılım oranı
        if ($room->questions->count() > 0 && $stats['total_participants'] > 0) {
            $totalResponseRate = $room->questions->sum(function ($q) use ($stats) {
                return ($q->answers->count() / $stats['total_participants']) * 100;
            });
            $stats['avg_response_rate'] = round($totalResponseRate / $room->questions->count(), 1);
        }
        
        // En çok/az cevaplanan sorular
        $stats['most_answered'] = collect($stats['questions_data'])->sortByDesc('answer_count')->first();
        $stats['least_answered'] = collect($stats['questions_data'])->sortBy('answer_count')->first();
        
        return view('reports.room', [
            'room' => $room,
            'stats' => $stats
        ]);
    }
}