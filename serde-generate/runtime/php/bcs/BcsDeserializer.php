<?php


class BcsDeserializer extends BinaryDeserializer
{

    public function deserialize_f32(): float
    {
        throw new DeserializationError("Not implemented: deserialize_f32");
    }

    public function deserialize_f64(): float
    {
        throw new DeserializationError("Not implemented: deserialize_f64");
    }

    private function deserialize_uleb128_as_u32(): int
    {
        $value = 0;
        for ($shift = 0; $shift < 32; $shift += 7) {
            $x = $this->getByte();
            $digit = (int)($x & 0x7F);
            $value = $value | ((int)$digit << $shift);
            if (($value < 0) || ($value > PHP_INT_MAX)) {
                throw new DeserializationError("Overflow while parsing uleb128-encoded uint32 value");
            }

            if ($digit == $x) {
                if ($shift > 0 && $digit == 0) {
                    throw new DeserializationError("Invalid uleb128 number (unexpected zero digit)");
                }
                return (int)$value;
            }
        }
        throw new DeserializationError("Overflow while parsing uleb128-encoded uint32 value");
    }

    public function deserialize_len(): int
    {
        return $this->deserialize_uleb128_as_u32();
    }

    public function deserialize_variant_index(): int
    {
        return $this->deserialize_uleb128_as_u32();
    }

    public function check_that_key_slices_are_increasing($key1, $key2)
    {
        if (Slice::compare_bytes($this->input->array(), $key1, $key2) >= 0) {
            throw new DeserializationError("Error while decoding map: keys are not serialized in the expected order");
        }
    }
}