<?php


class Slice
{


    public $start;
    public $end;


    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

// Lexicographic comparison between the (unsigned!) bytes referenced by `slice1` and `slice2`
// into `content`.
    public static function compare_bytes(array $content, Slice $slice1, Slice $slice2): int
    {
        $start1 = $slice1->start;
        $end1 = $slice1->end;
        $start2 = $slice2->start;
        $end2 = $slice2->end;
        for ($i = 0; $i < $end1 - $start1; $i++) {
            $byte1 = $content[$start1 + $i] & 0xFF;
            if ($start2 + $i >= $end2) {
                return 1;
            }
            $byte2 = $content[$start2 + $i] & 0xFF;
            if ($byte1 > $byte2) {
                return 1;
            }
            if ($byte1 < $byte2) {
                return -1;
            }
        }
        if ($end2 - $start2 > $end1 - $start1) {
            return -1;
        }
        return 0;
    }
}