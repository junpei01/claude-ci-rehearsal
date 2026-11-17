<?php

declare(strict_types=1);

namespace ManualConverter;

/**
 * 変換の前のチェック
 */
class ManualValidator
{
    /**
     * @return string[] エラーの一覧（空ならOK）
     */
    public function validate(Manual $manual): array
    {
        $errors = [];

        if ($manual->productName === '') {
            $errors[] = '製品名がありません';
        }

        if ($manual->revisionDate !== ''
            && !preg_match('/^\d{4}[-\/]\d{1,2}[-\/]\d{1,2}$/', $manual->revisionDate)) {
            $errors[] = '改訂日の形式が正しくありません: ' . $manual->revisionDate;
        }

        foreach ($manual->sections as $section) {
            $this->checkSection($section, $errors);
        }

        return $errors;
    }

    /**
     * @param string[] $errors
     */
    private function checkSection(Section $section, array &$errors): void
    {
        if ($section->title === '') {
            $errors[] = '見出しの無い章があります';
        }
        foreach ($section->sections as $child) {
            $this->checkSection($child, $errors);
        }
    }
}
