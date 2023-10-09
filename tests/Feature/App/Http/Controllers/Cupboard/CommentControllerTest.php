<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Facades\Conversion;
use App\Models\Cupboard\{Comment, Post, User, Reaction };
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Support\Str;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/CommentControllerTest.php --filter={test-name}
class CommentControllerTest extends TestCase
{
    protected $user;
    protected $post;
    protected $comment;
    protected $payload;

    // TODO: complete validation && incomplete requests tests
    // TODO: update tests with Products && Posts father's model

    public function setUp(): void
    {
        parent::setUp();
        $this->post = Post::factory()->withReview()->create();
        $this->payload = $this->createPayload();
    }

    /** @test */
    function get_comment_list_success()
    {
        $quantity = rand(2, 9);
        $this->putComment($quantity);
        $response = $this->requestResource('GET', "comments", [
            'model_type' => Post::class,
            'model_id'   => $this->post->uuid
        ]);
        $this->assertResponseSuccess($response);
        $this->assertTrue(count($this->getData($response)) == $quantity);
    }

    /** @test */
    function get_comment_list_fail_for_model_type_validation()
    {
        $this->putComment(2);
        $response = $this->requestResource('GET', "comments", [
            'model_type' => null,
            'model_id'   => $this->post->uuid
        ]);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message == $this->validationTranslation('model_type'));
        
    }

    /** @test */
    function get_comment_list_fail_for_model_id_validation()
    {
        $this->putComment();
        $response = $this->requestResource('GET', "comments", [
            'model_type' => Post::class,
            'model_id'   => null
        ]);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message == $this->validationTranslation('model_id'));
    }

    /** @test */
    function store_new_comment_in_post_success()
    {
        $response = $this->requestResource('POST', "comments", $this->payload);
        $this->assertResponseSuccess($response);
        $this->assertDatabaseHas('comments', [
            'uuid' => $response->getData()->data->id,
            'user_id' => User::first()->id,
            'model_type' => $this->payload['model_type'],
            'model_id' => $this->post->id,
            'comment' => $this->payload['comment']
        ]);
    }

    function store_new_comment_in_product_success()
    {
        //
    }

    /** @test */
    function show_comment_from_post_success()
    {
        $comment = $this->putComment()->first();
        $response = $this->requestResource('GET', "comments/{$comment->uuid}");
        $data = $this->getData($response);
        $this->assertResponseSuccess($response);
        $this->assertTrue(
            $data->id === $comment->uuid,
            $data->user_id === $comment->user_id,
            $data->model_type === $comment->model_type,
            $data->model_id === $comment->model_id,
            $data->comment === $comment->comment,
        );
    }

    /** @test */
    function update_comment_from_post_success()
    {
        $comment = $this->putComment()->first();
        $newComment = $this->faker->sentence();
        $response = $this->requestResource('PUT', "comments/{$comment->uuid}", [
            'user_id' => $comment->user_id,
            'model_type' => $comment->model_type,
            'model_id' => $comment->model_id,
            'comment' => $newComment
        ]);
        $this->assertResponseSuccess($response);
        $this->assertDatabaseHas('comments', [
            'uuid' => $comment->uuid,
            'user_id' => $comment->user_id,
            'model_type' => $comment->model_type,
            'model_id' => $comment->model_id,
            'comment' => $newComment,
        ]);
    }

    /** @test */
    function delete_comment_success()
    {                
        $comment = $this->putComment()->first();
        $response = $this->requestResource('DELETE', "comments/{$comment->uuid}");
        $this->assertResponseSuccess($response);
        $this->assertDatabaseMissing('comments', [
            'uuid' => $comment->uuid,
            'user_id' => $comment->user_id,
            'model_type' => $comment->model_type,
            'model_id' => $comment->model_id,
            'deleted_at' => null
        ]);
    }

    private function putComment($quantity = 1)
    {
        return Comment::factory($quantity)->create([
            'user_id' => User::factory()->create()->id,
            'model_type' => Post::class,
            'model_id' => $this->post->id
        ]);
    }

    private function createPayload()
    {
        return [
            'user_id' => User::first()->uuid,
            'model_type' => Post::class,
            'model_id' => $this->post->uuid,
            'comment' => $this->faker->sentence()
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.comments.validation.' . $key);
    }
}
