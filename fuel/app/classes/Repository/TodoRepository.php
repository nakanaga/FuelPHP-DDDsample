<?php

namespace Repository;

use Domain\Entity\Todo;
use Domain\Factory\TodoFactory;
use Domain\Repository\TodoRepositoryInterface;
use Domain\ValueObject\TodoId;

/**
 * Todoリポジトリ実装
 *
 * @package Repository
 */
class TodoRepository implements TodoRepositoryInterface
{
    /** @var TodoFactory */
    private TodoFactory $factory;

    public function __construct()
    {
        $this->factory = new TodoFactory();
    }

    /**
     * IDでTodoを検索する
     *
     * @param TodoId $id 検索するTodoのID
     * @return Todo|null 見つかった場合はTodo、見つからない場合はnull
     */
    public function findById(TodoId $id): ?Todo
    {
        $model = \Model_Todo::find($id->getValue());

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    /**
     * 全てのTodoを取得する
     *
     * @return Todo[]
     */
    public function findAll(): array
    {
        /** @var \Model_Todo[] $models */
        $models = \Model_Todo::find('all', [
            'order_by' => ['created_at' => 'desc'],
        ]);

        $result = [];
        foreach ($models as $model) {
            $result[] = $this->toEntity($model);
        }

        return $result;
    }

    /**
     * Todoを保存する
     *
     * @param Todo $todo 保存するTodo
     * @return void
     */
    public function save(Todo $todo): void
    {
        if ($todo->getId() === null) {
            $model = \Model_Todo::forge([
                'title' => $todo->getTitle()->getValue(),
                'status' => $todo->getStatus()->getValue(),
                'completed_at' => $todo->getCompletedAt()?->format('Y-m-d H:i:s'),
            ]);
            $model->save();

            $todo->assignId(new TodoId((int)$model->id));
        } else {
            $model = \Model_Todo::find($todo->getId()->getValue());
            if ($model instanceof \Model_Todo) {
                $model->title = $todo->getTitle()->getValue();
                $model->status = $todo->getStatus()->getValue();
                $model->completed_at = $todo->getCompletedAt()?->format('Y-m-d H:i:s');
                $model->save();
            }
        }
    }

    /**
     * Todoを削除する
     *
     * @param TodoId $id 削除するTodoのID
     * @return void
     */
    public function delete(TodoId $id): void
    {
        $model = \Model_Todo::find($id->getValue());
        if ($model instanceof \Model_Todo) {
            $model->delete();
        }
    }

    /**
     * ORMモデルからエンティティに変換する（ファクトリを使用）
     *
     * @param \Model_Todo $model ORMモデル
     * @return Todo
     */
    private function toEntity(\Model_Todo $model): Todo
    {
        return $this->factory->reconstruct(
            (int)$model->id,
            $model->title,
            $model->status,
            $model->created_at,
            $model->completed_at
        );
    }
}
