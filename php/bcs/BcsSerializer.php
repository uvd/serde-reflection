<?php


class BcsSerializer extends BinarySerializer
{

    const MAX_LENGTH = PHP_INT_MAX;

    public function serialize_f32(float $value)
    {
        throw new SerializationError("Not implemented: serialize_f32");
    }

    public function serialize_f64(float $value)
    {
        throw new SerializationError("Not implemented: serialize_f64");
    }

    private function serialize_u32_as_uleb128(int $value)
    {
        while (($value >> 7) != 0) {
            $output->write(($value & 0x7f) | 0x80);
            $value = $value >> 7;
        }
        $output->write($value);
    }

    public function serialize_len(int $value)
    {
        if (($value < 0) || ($value > PHP_INT_MAX)) {
            throw new SerializationError("Incorrect length $value");
        }
        serialize_u32_as_uleb128((int)$value);
    }

    public function serialize_variant_index(int $value)
    {
        serialize_u32_as_uleb128($value);
    }

    public function sort_map_entries(array $offsets)
    {
        if (count($offsets) <= 1) {
            return;
        }
        $offset0 = $offsets[0];
        $content = $output->etBuffer();
        $slices = array();
        for ($i = 0; $i < count($offsets); $i++) {
            $slices[$i] = new Slice($offsets[$i], $offsets[$i + 1]);
        }
        $slices[count($offsets) - 1] = new Slice($offsets[count($offsets) - 1], $output->size());


        sort($slices);


//        $old_content = new byte[output . size() - $offset0];
//        System . arraycopy(content, offset0, old_content, 0, output . size() - $offset0);

        $position = $offset0;
        for ($i = 0; $i < count($offsets); $i++) {
            $start = $slices[$i]->start;
            $end = $slices[$i]->end;
            //     System . arraycopy(old_content, start - offset0, content, position, end - start);
            $position += $end - $start;
        }
    }


}