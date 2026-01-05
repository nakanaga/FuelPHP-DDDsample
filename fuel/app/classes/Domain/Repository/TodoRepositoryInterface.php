<?php

namespace Domain\Repository;

use Domain\Entity\Todo;
use Domain\ValueObject\TodoId;

/**
 * Todoリポジトリインターフェース
 *
 * @package Domain\Repository
 */
interface TodoRepositoryInterface
{
    /**
     * IDでTodoを検索する
     *
     * @param TodoId $id 検索するTodoのID
     * @return Todo|null 見つかった場合はTodo、見つからない場合はnull
     */
    public function findById(TodoId $id): ?Todo;

    /**
     * 全てのTodoを取得する
     *
     * @return Todo[]
     */
    public function findAll(): array;

    /**
     * Todoを保存する
     *
     * @param Todo $todo 保存するTodo
     * @return void
     */
    public function save(Todo $todo): void;

    /**
     * Todoを削除する
     *
     * @param TodoId $id 削除するTodoのID
     * @return void
     */
    public function delete(TodoId $id): void;
}
