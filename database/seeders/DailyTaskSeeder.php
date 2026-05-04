<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyTask;

class DailyTaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            // Gaming (manual — can't track in-game actions)
            ['title' => 'Play a match in your main game', 'description' => 'Jump into a ranked or casual game and play at least one full match.', 'xp' => 15, 'category' => 'gaming', 'auto_complete_rule' => null],
            ['title' => 'Try a new game genre', 'description' => 'Step outside your comfort zone and try a game you haven\'t played before.', 'xp' => 20, 'category' => 'gaming', 'auto_complete_rule' => null],
            ['title' => 'Win 3 matches', 'description' => 'Secure 3 victories in any game mode today.', 'xp' => 25, 'category' => 'gaming', 'auto_complete_rule' => null],
            ['title' => 'Practice your aim for 15 minutes', 'description' => 'Use an aim trainer or practice range to sharpen your skills.', 'xp' => 10, 'category' => 'gaming', 'auto_complete_rule' => null],
            ['title' => 'Watch a gameplay tutorial', 'description' => 'Learn something new by watching a guide for one of your games.', 'xp' => 10, 'category' => 'gaming', 'auto_complete_rule' => null],
            ['title' => 'Try a different role or character', 'description' => 'Switch things up and play a role you don\'t usually pick.', 'xp' => 15, 'category' => 'gaming', 'auto_complete_rule' => null],
            ['title' => 'Complete a daily challenge in-game', 'description' => 'Finish any daily challenge offered by the game itself.', 'xp' => 15, 'category' => 'gaming', 'auto_complete_rule' => null],
            ['title' => 'Reach a new personal best', 'description' => 'Set a new high score, rank, or personal record.', 'xp' => 30, 'category' => 'gaming', 'auto_complete_rule' => null],

            // Social (automated where possible)
            ['title' => 'Send a message to a player', 'description' => 'Reach out and start a conversation with another gamer on the platform.', 'xp' => 10, 'category' => 'social', 'auto_complete_rule' => 'message:1'],
            ['title' => 'Follow 3 new players', 'description' => 'Discover and follow 3 players who share your interests.', 'xp' => 15, 'category' => 'social', 'auto_complete_rule' => 'follow:3'],
            ['title' => 'Uplift someone\'s profile', 'description' => 'Show appreciation by uplifting another player\'s account.', 'xp' => 10, 'category' => 'social', 'auto_complete_rule' => 'uplift:1'],
            ['title' => 'Comment on a post', 'description' => 'Leave a thoughtful comment on someone else\'s wall post.', 'xp' => 10, 'category' => 'social', 'auto_complete_rule' => 'comment:1'],
            ['title' => 'Like 5 posts', 'description' => 'Spread positivity by liking posts from other users.', 'xp' => 10, 'category' => 'social', 'auto_complete_rule' => 'like:5'],
            ['title' => 'Play with a Nexus friend', 'description' => 'Team up with someone you met on this platform for a game session.', 'xp' => 25, 'category' => 'social', 'auto_complete_rule' => null],
            ['title' => 'Invite a friend to Nexus', 'description' => 'Share the platform with a gaming buddy who hasn\'t joined yet.', 'xp' => 20, 'category' => 'social', 'auto_complete_rule' => null],

            // Profile (manual — no reliable way to detect some of these)
            ['title' => 'Update your bio', 'description' => 'Refresh your profile bio to keep it current and interesting.', 'xp' => 10, 'category' => 'profile', 'auto_complete_rule' => null],
            ['title' => 'Add a new game to your library', 'description' => 'Expand your gaming profile by adding a game you play.', 'xp' => 10, 'category' => 'profile', 'auto_complete_rule' => null],
            ['title' => 'Update your rank in a game', 'description' => 'Make sure your ranks are up to date on your profile.', 'xp' => 10, 'category' => 'profile', 'auto_complete_rule' => null],
            ['title' => 'Customize your profile theme', 'description' => 'Try a new color scheme or background image for your profile.', 'xp' => 10, 'category' => 'profile', 'auto_complete_rule' => null],
            ['title' => 'Upload a new profile picture', 'description' => 'Give your avatar a fresh look with a new profile photo.', 'xp' => 15, 'category' => 'profile', 'auto_complete_rule' => null],
            ['title' => 'Write a wall post', 'description' => 'Share what you\'re playing, a highlight, or just say hello on your wall.', 'xp' => 10, 'category' => 'profile', 'auto_complete_rule' => 'post:1'],
            ['title' => 'Update your availability', 'description' => 'Let others know when you\'re available to play.', 'xp' => 10, 'category' => 'profile', 'auto_complete_rule' => null],

            // General / App
            ['title' => 'Browse the Player Finder', 'description' => 'Explore the Player Finder and discover new teammates.', 'xp' => 10, 'category' => 'general', 'auto_complete_rule' => null],
            ['title' => 'Check your notifications', 'description' => 'Catch up on what\'s been happening — check your latest notifications.', 'xp' => 5, 'category' => 'general', 'auto_complete_rule' => null],
            ['title' => 'Read all unread messages', 'description' => 'Clear your inbox by reading and replying to unread messages.', 'xp' => 10, 'category' => 'general', 'auto_complete_rule' => null],
            ['title' => 'Complete all daily tasks', 'description' => 'Finish every task on today\'s list for bonus XP.', 'xp' => 50, 'category' => 'general', 'auto_complete_rule' => 'all_others_done'],
            ['title' => 'Log in to Nexus', 'description' => 'You\'re already here! Just showing up counts.', 'xp' => 5, 'category' => 'general', 'auto_complete_rule' => 'login'],
            ['title' => 'Explore a trending player\'s profile', 'description' => 'Visit the profile of a popular or top-uplifted player.', 'xp' => 10, 'category' => 'general', 'auto_complete_rule' => null],
        ];

        foreach ($tasks as $task) {
            DailyTask::updateOrCreate(
                ['title' => $task['title']],
                $task
            );
        }
    }
}
