<?php

namespace App\Console\Commands\Cupboard;

use App\Models\Traits\GetModelTrait;
use Illuminate\Console\Command;

class CreateReviewCommand extends Command
{
    use GetModelTrait;
    protected $maxScore = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cboard:make-review {model_type} {model_id}';

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
        $modelType = $this->argument('model_type');
        $modelId = $this->argument('model_id');
        $model = $this->getModel($modelType, $modelId);

        $ratedReview = $this->calculateReview($model->reactions);
        $reviewRec = $model->review;
        $reviewRec->review = $ratedReview;
        $reviewRec->save();

        return 0;
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
