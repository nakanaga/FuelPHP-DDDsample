<?php

namespace Domain\ValueObject;

/**
 * Todoタイトルを表す値オブジェクト
 *
 * @package Domain\ValueObject
 */
class TodoTitle
{
    /** @var string */
    private string $value;

    /** @var int タイトルの最大文字数 */
    private const MAX_LENGTH = 100;

    /**
     * @param string $value タイトル文字列
     * @throws \InvalidArgumentException タイトルが空または最大文字数を超過した場合
     */
    public function __construct(string $value)
    {
        $value = trim($value);

        if (empty($value)) {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        if (mb_strlen($value) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException('Title cannot exceed ' . self::MAX_LENGTH . ' characters');
        }

        $this->value = $value;
    }

    /**
     * タイトル文字列を取得する
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 他のTodoTitleと等価か判定する
     *
     * @param self $other 比較対象
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
