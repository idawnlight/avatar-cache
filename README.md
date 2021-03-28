# Avatar Cache

Simple & fast cache for small objects (currently oriented to avatars).

WARNING: the project is nothing more than a toy, not production-ready. DON'T USE UNLESS YOU KNOW WHAT YOU ARE DOING.

# Configure

Take a look at `Config.example.php` for more information. Remember to copy `Config.example.php` to `Config.php` and modify the fields.

# Performance

Aimed to maximize the performance as a project based on php with swoole, it still needs to be refactored greatly.

Currently, the performance is about half of the rust + actix based `idawnlight/one-rust`, a project much more like a toy. A short-term target is to surpass that.