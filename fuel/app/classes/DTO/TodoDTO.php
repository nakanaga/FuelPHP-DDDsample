<?php

namespace DTO;

use Domain\Entity\Todo;

/**
 * Todoデータ転送オブジェクト
 *
 * @package DTO
 */
class TodoDTO
{
    /** @var int|null TodoのID */
    public readonly ?int $id;

    /** @var string タイトル */
    public readonly string $title;

    /** @var string ステータス */
    public readonly string $status;

    /** @var string 作成日時 */
    public readonly string $createdAt;

    /** @var string|null 完了日時 */
    public readonly ?string $completedAt;

    /**
     * @param int|null $id TodoのID
     * @param string $title タイトル
     * @param string $status ステータス
     * @param string $createdAt 作成日時
     * @param string|null $completedAt 完了日時
     */
    public function __construct(
        ?int $id,
        string $title,
        string $status,
        string $createdAt,
        ?string $completedAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->completedAt = $completedAt;
    }

    /**
     * エンティティからDTOを生成する
     *
     * @param Todo $todo Todoエンティティ
     * @return self
     */
    public static function fromEntity(Todo $todo): self
    {
        return new self(
            $todo->getId()?->getValue(),
            $todo->getTitle()->getValue(),
            $todo->getStatus()->getValue(),
            $todo->getCreatedAt()->format('Y-m-d H:i:s'),
            $todo->getCompletedAt()?->format('Y-m-d H:i:s')
        );
    }

    /**
     * 配列に変換する
     *
     * @return array{id: int|null, title: string, status: string, created_at: string, completed_at: string|null}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'completed_at' => $this->completedAt,
        ];
    }
}
