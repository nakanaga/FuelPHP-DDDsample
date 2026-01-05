<?php

namespace Domain\Entity;

use Domain\ValueObject\TodoId;
use Domain\ValueObject\TodoStatus;
use Domain\ValueObject\TodoTitle;

/**
 * Todoエンティティ
 *
 * @package Domain\Entity
 */
class Todo
{
    /** @var TodoId|null */
    private ?TodoId $id;

    /** @var TodoTitle */
    private TodoTitle $title;

    /** @var TodoStatus */
    private TodoStatus $status;

    /** @var \DateTimeImmutable */
    private \DateTimeImmutable $createdAt;

    /** @var \DateTimeImmutable|null */
    private ?\DateTimeImmutable $completedAt;

    /**
     * @param TodoId|null $id ID（新規作成時はnull）
     * @param TodoTitle $title タイトル
     * @param TodoStatus $status ステータス
     * @param \DateTimeImmutable $createdAt 作成日時
     * @param \DateTimeImmutable|null $completedAt 完了日時
     */
    public function __construct(
        ?TodoId $id,
        TodoTitle $title,
        TodoStatus $status,
        \DateTimeImmutable $createdAt,
        ?\DateTimeImmutable $completedAt = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->completedAt = $completedAt;
    }

    /**
     * 新規Todoを作成する
     *
     * @param TodoTitle $title タイトル
     * @return self
     */
    public static function create(TodoTitle $title): self
    {
        return new self(
            null,
            $title,
            TodoStatus::pending(),
            new \DateTimeImmutable()
        );
    }

    /**
     * @return TodoId|null
     */
    public function getId(): ?TodoId
    {
        return $this->id;
    }

    /**
     * @return TodoTitle
     */
    public function getTitle(): TodoTitle
    {
        return $this->title;
    }

    /**
     * @return TodoStatus
     */
    public function getStatus(): TodoStatus
    {
        return $this->status;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    /**
     * タイトルを変更する
     *
     * @param TodoTitle $title 新しいタイトル
     * @return void
     */
    public function changeTitle(TodoTitle $title): void
    {
        $this->title = $title;
    }

    /**
     * Todoを完了する
     *
     * @return void
     * @throws \DomainException 既に完了済みの場合
     */
    public function complete(): void
    {
        if ($this->status->isCompleted()) {
            throw new \DomainException('Todo is already completed');
        }

        $this->status = TodoStatus::completed();
        $this->completedAt = new \DateTimeImmutable();
    }

    /**
     * Todoを再開する
     *
     * @return void
     * @throws \DomainException 既に未完了の場合
     */
    public function reopen(): void
    {
        if ($this->status->isPending()) {
            throw new \DomainException('Todo is already pending');
        }

        $this->status = TodoStatus::pending();
        $this->completedAt = null;
    }

    /**
     * IDを割り当てる
     *
     * @param TodoId $id 割り当てるID
     * @return void
     * @throws \DomainException 既にIDが割り当て済みの場合
     */
    public function assignId(TodoId $id): void
    {
        if ($this->id !== null) {
            throw new \DomainException('Id is already assigned');
        }
        $this->id = $id;
    }
}
