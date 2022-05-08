<?php


interface Deserializer
{
    function deserialize_str(): string;

    function deserialize_bytes(): string;

    function deserialize_bool(): bool;

    function deserialize_unit(): int;

    function deserialize_char(): string;

    function deserialize_f32(): float;

    function deserialize_f64(): float;

    function deserialize_u8(): int;

    function deserialize_u16(): int;

    function deserialize_u32(): int;

    function deserialize_u64(): int;

    function deserialize_u128(): int;

    function deserialize_i8(): int;

    function deserialize_i16(): int;

    function deserialize_i32(): int;

    function deserialize_i64(): int;

    function deserialize_i128(): int;

    function deserialize_len(): int;

    function deserialize_variant_index(): int;

    function deserialize_option_tag(): bool;

    function increase_container_depth();

    function decrease_container_depth();

    function get_buffer_offset(): int;

    function check_that_key_slices_are_increasing($key1, $key2);

}