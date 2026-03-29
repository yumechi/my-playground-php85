<?php

// このコメントはASTに含まれるか？

/**
 * DocBlock コメント
 * @param int $x
 * @return int
 */
function double(int $x): int {
    // インラインコメント
    return $x * 2; // 行末コメント
}

/* ブロックコメント */
$result = double(21);
echo $result;
