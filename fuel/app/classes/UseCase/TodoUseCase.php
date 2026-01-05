<?php

namespace UseCase;

use Domain\Entity\Todo;
use Domain\Factory\TodoFactory;
use Domain\Repository\TodoRepositoryInterface;
use Domain\Service\TodoDomainService;
use Domain\ValueObject\TodoId;
use DTO\TodoDTO;

/**
 * Todoユースケース
 *
 * @package UseCase
 */
class TodoUseCase
{
    /** @var TodoRepositoryInterface */
    private TodoRepositoryInterface $repository;

    /** @var TodoDomainService */
    private TodoDomainService $domainService;

    /** @var TodoFactory */
    private TodoFactory $factory;

    /**
     * @param TodoRepositoryInterface $repository Todoリポジトリ
     * @param TodoDomainService $domainService Todoドメインサービス
     * @param TodoFactory $factory Todoファクトリ
     */
    public function __construct(
        TodoRepositoryInterface $repository,
        TodoDomainService $domainService,
        TodoFactory $factory
    ) {
        $this->repository = $repository;
        $this->domainService = $domainService;
        $this->factory = $factory;
    }

    /**
     * 新規Todoを作成する
     *
     * @param string $title タイトル
     * @return TodoDTO 作成されたTodoのDTO
     * @throws \InvalidArgumentException タイトルが不正な場合
     * @throws \DomainException 作成上限に達している場合、または重複している場合
     */
    public function create(string $title): TodoDTO
    {
        \DB::start_transaction();

        try {
            if ($this->domainService->hasReachedDailyLimit()) {
                throw new \DomainException('1日の作成上限に達しました');
            }

            $todo = $this->factory->createNew($title);

            if ($this->domainService->isDuplicate($todo)) {
                throw new \DomainException('同じタイトルのTodoが既に存在します');
            }

            $this->repository->save($todo);

            \DB::commit_transaction();

            return TodoDTO::fromEntity($todo);
        } catch (\Exception $e) {
            \DB::rollback_transaction();
            throw $e;
        }
    }

    /**
     * 全てのTodoを取得する
     *
     * @return TodoDTO[]
     */
    public function getAll(): array
    {
        $todos = $this->repository->findAll();

        return array_map(
            fn(Todo $todo) => TodoDTO::fromEntity($todo),
            $todos
        );
    }

    /**
     * Todoを完了する
     *
     * @param int $id TodoのID
     * @return TodoDTO 完了したTodoのDTO
     * @throws \DomainException Todoが見つからない場合、または既に完了済みの場合
     */
    public function complete(int $id): TodoDTO
    {
        \DB::start_transaction();

        try {
            $todoId = new TodoId($id);
            $todo = $this->repository->findById($todoId);

            if ($todo === null) {
                throw new \DomainException('Todo not found');
            }

            $todo->complete();
            $this->repository->save($todo);

            \DB::commit_transaction();

            return TodoDTO::fromEntity($todo);
        } catch (\Exception $e) {
            \DB::rollback_transaction();
            throw $e;
        }
    }

    /**
     * 全ての未完了Todoを完了にする
     *
     * @return int 完了にしたTodo数
     */
    public function completeAll(): int
    {
        return $this->domainService->completeAll();
    }

    /**
     * 未完了のTodo数を取得する
     *
     * @return int 未完了のTodo数
     */
    public function countPending(): int
    {
        return $this->domainService->countPendingTodos();
    }

    /**
     * 完了率を取得する
     *
     * @return float 完了率（0.0〜1.0）
     */
    public function getCompletionRate(): float
    {
        return $this->domainService->calculateCompletionRate();
    }
}
