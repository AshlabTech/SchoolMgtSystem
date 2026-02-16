<?php

namespace Database\Seeders;

use App\Models\ResultComment;
use Illuminate\Database\Seeder;

class ResultCommentsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['comment' => 'Excellent performance. Keep it up.', 'type' => 'teacher', 'sort_order' => 1, 'is_default' => true],
            ['comment' => 'Good effort. There is room for improvement.', 'type' => 'teacher', 'sort_order' => 2, 'is_default' => false],
            ['comment' => 'Shows steady progress in class.', 'type' => 'teacher', 'sort_order' => 3, 'is_default' => false],
            ['comment' => 'An outstanding term. Keep soaring higher.', 'type' => 'principal', 'sort_order' => 1, 'is_default' => true],
        ];

        foreach ($defaults as $comment) {
            ResultComment::updateOrCreate(
                ['comment' => $comment['comment'], 'type' => $comment['type']],
                ['is_active' => true, 'is_default' => $comment['is_default'], 'sort_order' => $comment['sort_order']]
            );
        }
    }
}
