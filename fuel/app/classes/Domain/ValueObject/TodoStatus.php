<?php

namespace Domain\ValueObject;

/**
 * Todoステータスを表す値オブジェクト
 *
 * @package Domain\ValueObject
 */
class TodoStatus
{
    /** @var string 未完了ステータス */
    private const PENDING = 'pending';

    /** @var string 完了ステータス */
    private const COMPLETED = 'completed';

    /** @var string */
    private string $value;

    /**
     * @param string $value ステータス値
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * 未完了ステータスを生成する
     *
     * @return self
     */
    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    /**
     * 完了ステータスを生成する
     *
     * @return self
     */
    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    /**
     * 文字列からステータスを生成する
     *
     * @param string $value ステータス文字列
     * @return self
     * @throws \InvalidArgumentException 無効なステータス値の場合
     */
    public static function fromString(string $value): self
    {
        if (!in_array($value, [self::PENDING, self::COMPLETED], true)) {
            throw new \InvalidArgumentException('Invalid status: ' . $value);
        }
        return new self($value);
    }

    /**
     * ステータス値を取得する
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 未完了かどうかを判定する
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->value === self::PENDING;
    }

    /**
     * 完了かどうかを判定する
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->value === self::COMPLETED;
    }

    /**
     * 他のTodoStatusと等価か判定する
     *
     * @param self $other 比較対象
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
