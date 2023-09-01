<?php

namespace App\Console\Commands\Cupboard;

use App\Models\Cupboard\Post;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class CreateReviewCommand extends Command
{
    protected $maxScore = 5;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cboard:make-review {post_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create or update the review of any Post';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $postId = $this->argument('post_id');
        $post = Post::where('id', $postId)->firstOrFail();

        $ratedReview = $this->calculateReview($post->reactions);
        $reviewRec = $post->review;
        $reviewRec->review = $ratedReview;
        $reviewRec->save();

        return 1;
    }

    protected function calculateReview($reactions)
    {
        $totalReactions = count($reactions);
        $positiveReacts = 0;
        $reactions->map(function ($item, $key) use (&$positiveReacts) {
            if ($item->reaction === true) {
                $positiveReacts = $positiveReacts + 1;
            }
        });

        $factor = $this->maxScore * $positiveReacts;
        $review = $factor / $totalReactions;

        return $review;
    }
}
