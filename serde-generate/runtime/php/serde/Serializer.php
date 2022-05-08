<?php


interface Serializer
{
    function serialize_str(string $value);

    function serialize_bytes(string $value);

    function serialize_bool(bool $value);

    function serialize_unit(int $value);

    function serialize_char(string $value);

    function serialize_f32(float $value);

    function serialize_f64(float $value);

    function serialize_u8(int $value);

    function serialize_u16(int $value);

    function serialize_u32(int $value);

    function serialize_u64(int $value);

    function serialize_u128(int $value);

    function serialize_i8(int $value);

    function serialize_i16(int $value);

    function serialize_i32(int $value);

    function serialize_i64(int $value);

    function serialize_i128(int $value);

    function serialize_len(int $value);

    function serialize_variant_index(int $value);

    function serialize_option_tag(bool $value);

    function increase_container_depth();

    function decrease_container_depth();

    function get_buffer_offset(): int;

    function sort_map_entries(array $offsets);

    function get_bytes();

}