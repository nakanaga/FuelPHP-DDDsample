<?php

namespace Domain\ValueObject;

/**
 * Todo識別子を表す値オブジェクト
 *
 * @package Domain\ValueObject
 */
class TodoId
{
    /** @var int */
    private int $value;

    /**
     * @param int $value TodoのID値
     * @throws \InvalidArgumentException IDが負の値の場合
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('TodoId must be a positive integer');
        }
        $this->value = $value;
    }

    /**
     * ID値を取得する
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * 他のTodoIdと等価か判定する
     *
     * @param self $other 比較対象
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
