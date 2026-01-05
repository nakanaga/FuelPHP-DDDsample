<?php

namespace Domain\Factory;

use Domain\Entity\Todo;
use Domain\ValueObject\TodoId;
use Domain\ValueObject\TodoStatus;
use Domain\ValueObject\TodoTitle;

/**
 * Todoエンティティのファクトリ
 *
 * @package Domain\Factory
 */
class TodoFactory
{
    /**
     * 新規Todoを作成する
     *
     * @param string $title タイトル
     * @return Todo
     */
    public function createNew(string $title): Todo
    {
        return new Todo(
            null,
            new TodoTitle($title),
            TodoStatus::pending(),
            new \DateTimeImmutable()
        );
    }

    /**
     * DBレコードからTodoを再構築する
     *
     * @param int $id ID
     * @param string $title タイトル
     * @param string $status ステータス
     * @param string $createdAt 作成日時
     * @param string|null $completedAt 完了日時
     * @return Todo
     */
    public function reconstruct(
        int $id,
        string $title,
        string $status,
        string $createdAt,
        ?string $completedAt
    ): Todo {
        return new Todo(
            new TodoId($id),
            new TodoTitle($title),
            TodoStatus::fromString($status),
            new \DateTimeImmutable($createdAt),
            $completedAt ? new \DateTimeImmutable($completedAt) : null
        );
    }
}
