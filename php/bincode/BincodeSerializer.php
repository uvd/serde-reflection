<?php


class BincodeSerializer extends BinarySerializer
{

    public function serialize_f32(float $value)
    {
        serialize_i32($value);
    }

    public function serialize_f64(float $value)
    {
        serialize_i64($value);
    }

    public function serialize_len(int $value)
    {
        serialize_u64($value);
    }

    public function serialize_variant_index(int $value)
    {
        serialize_u32($value);
    }

    public function sort_map_entries(array $offsets)
    {
        // Not required by the format.
    }
}