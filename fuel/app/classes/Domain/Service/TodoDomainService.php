<?php

namespace Domain\Service;

use Domain\Entity\Todo;
use Domain\Repository\TodoRepositoryInterface;

/**
 * Todoドメインサービス
 *
 * 単一のエンティティに属さないドメインロジックを担当する
 *
 * @package Domain\Service
 */
class TodoDomainService
{
    /** @var int 1日あたりのTodo作成上限 */
    private const DAILY_TODO_LIMIT = 10;

    /** @var TodoRepositoryInterface */
    private TodoRepositoryInterface $repository;

    /**
     * @param TodoRepositoryInterface $repository Todoリポジトリ
     */
    public function __construct(TodoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 同じタイトルのTodoが存在するか確認する
     *
     * @param Todo $todo 確認対象のTodo
     * @return bool 重複している場合はtrue
     */
    public function isDuplicate(Todo $todo): bool
    {
        $allTodos = $this->repository->findAll();

        foreach ($allTodos as $existingTodo) {
            if ($existingTodo->getId()?->equals($todo->getId() ?? new \Domain\ValueObject\TodoId(0))) {
                continue;
            }

            if ($existingTodo->getTitle()->equals($todo->getTitle())) {
                return true;
            }
        }

        return false;
    }

    /**
     * 本日のTodo作成上限に達しているか確認する
     *
     * @return bool 上限に達している場合はtrue
     */
    public function hasReachedDailyLimit(): bool
    {
        $allTodos = $this->repository->findAll();
        $today = new \DateTimeImmutable('today');

        $todayCount = 0;
        foreach ($allTodos as $todo) {
            if ($todo->getCreatedAt() >= $today) {
                $todayCount++;
            }
        }

        return $todayCount >= self::DAILY_TODO_LIMIT;
    }

    /**
     * 未完了のTodo数を取得する
     *
     * @return int 未完了のTodo数
     */
    public function countPendingTodos(): int
    {
        $allTodos = $this->repository->findAll();

        return count(array_filter(
            $allTodos,
            fn(Todo $todo) => $todo->getStatus()->isPending()
        ));
    }

    /**
     * 完了率を計算する
     *
     * @return float 完了率（0.0〜1.0）
     */
    public function calculateCompletionRate(): float
    {
        $allTodos = $this->repository->findAll();

        if (count($allTodos) === 0) {
            return 0.0;
        }

        $completedCount = count(array_filter(
            $allTodos,
            fn(Todo $todo) => $todo->getStatus()->isCompleted()
        ));

        return $completedCount / count($allTodos);
    }

    /**
     * 全ての未完了Todoを完了にする
     *
     * @return Todo[] 完了にしたTodoの配列
     */
    public function completeAll(): array
    {
        $allTodos = $this->repository->findAll();
        $completedTodos = [];

        foreach ($allTodos as $todo) {
            if ($todo->getStatus()->isPending()) {
                $todo->complete();
                $completedTodos[] = $todo;
            }
        }

        return $completedTodos;
    }
}
