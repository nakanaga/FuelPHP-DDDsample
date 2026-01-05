<?php

use Domain\Factory\TodoFactory;
use Domain\Service\TodoDomainService;
use Repository\TodoRepository;
use UseCase\TodoUseCase;

/**
 * Todoコントローラー
 *
 * @package Controller
 */
class Controller_Todo extends Controller
{
    /** @var TodoUseCase */
    private TodoUseCase $todoUseCase;

    /**
     * コントローラー実行前の初期化処理
     *
     * @return void
     */
    public function before()
    {
        parent::before();
        $repository = new TodoRepository();
        $domainService = new TodoDomainService($repository);
        $factory = new TodoFactory();
        $this->todoUseCase = new TodoUseCase($repository, $domainService, $factory);
    }

    /**
     * Todo一覧を表示する
     *
     * @return Response
     */
    public function action_index()
    {
        $todos = $this->todoUseCase->getAll();

        $data = [
            'todos' => array_map(fn($dto) => $dto->toArray(), $todos),
            'pending_count' => $this->todoUseCase->countPending(),
            'completion_rate' => $this->todoUseCase->getCompletionRate(),
        ];

        return Response::forge(View::forge('todo/index', $data));
    }

    /**
     * 新規Todoを作成する
     *
     * @return Response
     */
    public function action_create()
    {
        if (Input::method() === 'POST') {
            try {
                $title = Input::post('title');
                $this->todoUseCase->create($title);

                return Response::redirect('todo');
            } catch (InvalidArgumentException | DomainException $e) {
                $data = ['error' => $e->getMessage()];
                return Response::forge(View::forge('todo/create', $data));
            }
        }

        return Response::forge(View::forge('todo/create'));
    }

    /**
     * Todoを完了にする
     *
     * @param int $id TodoのID
     * @return Response
     */
    public function action_complete($id)
    {
        try {
            $this->todoUseCase->complete((int)$id);
        } catch (DomainException $e) {
            Session::set_flash('error', $e->getMessage());
        }

        return Response::redirect('todo');
    }

    /**
     * 全ての未完了Todoを完了にする
     *
     * @return Response
     */
    public function action_complete_all()
    {
        $count = $this->todoUseCase->completeAll();
        Session::set_flash('success', "{$count}件のTodoを完了しました");

        return Response::redirect('todo');
    }
}
