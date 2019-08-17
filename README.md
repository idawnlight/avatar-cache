# Avatar Cache

Extremely simple way to speed up avatar loading elegantly, with incredible performance.

# Configure

Look at `Config.example.php` for more information. Remember to copy `Config.example.php` as `Config.php`

# Performance

On my PC with Intel Core i5-4590, using Apache Bench. Windows 10 1903 + WSL. 32 threads, 1000 requests.

Command: `ab -c 16 -n 1000 http://localhost:9501/gravatar/605f8c6c64b8fcd514a0b53c6cc3680c`

> Note: All requests hit cache.

## Avatar Cache: Swoole Mode (WSL) (PHP 7.3.7)

```
Server Software:        NodeName
Server Hostname:        localhost
Server Port:            9501

Document Path:          /gravatar/605f8c6c64b8fcd514a0b53c6cc3680c
Document Length:        12931 bytes

Concurrency Level:      16
Time taken for tests:   1.131 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      13338000 bytes
HTML transferred:       12931000 bytes
Requests per second:    883.79 [#/sec] (mean)
Time per request:       18.104 [ms] (mean)
Time per request:       1.131 [ms] (mean, across all concurrent requests)
Transfer rate:          11511.72 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1   0.7      1       4
Processing:     1   17   3.6     16      29
Waiting:        1   10   4.6     10      27
Total:          2   18   3.8     17      29

Percentage of the requests served within a certain time (ms)
  50%     17
  66%     18
  75%     20
  80%     21
  90%     24
  95%     25
  98%     27
  99%     28
 100%     29 (longest request)
```

## Avatar Cache: PHP-FPM (Windows) (PHP 7.3.0)

```
Server Software:        NodeName
Server Hostname:        avatar.test
Server Port:            80

Document Path:          /gravatar/605f8c6c64b8fcd514a0b53c6cc3680c
Document Length:        12910 bytes

Concurrency Level:      16
Time taken for tests:   4.980 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      13312000 bytes
HTML transferred:       12910000 bytes
Requests per second:    200.81 [#/sec] (mean)
Time per request:       79.678 [ms] (mean)
Time per request:       4.980 [ms] (mean, across all concurrent requests)
Transfer rate:          2610.52 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    4   3.9      2      19
Processing:    31   75  12.2     74     116
Waiting:       21   73  12.0     72     114
Total:         32   79  12.0     77     117

Percentage of the requests served within a certain time (ms)
  50%     77
  66%     82
  75%     85
  80%     88
  90%     95
  95%    100
  98%    108
  99%    113
 100%    117 (longest request)
```

## [LoliLin/One](https://github.com/LoliLin/One): NodeJS v12.8.0 (Windows)

PHP seems to be a bit faster (Run away

> Note that I comment out all `console.log` since that will block the process.
> With `console.log`, the QPS is even a bit lower than PHP-FPM

```
Server Software:        Node
Server Hostname:        localhost
Server Port:            3000

Document Path:          /avatar/605f8c6c64b8fcd514a0b53c6cc3680c
Document Length:        1738 bytes

Concurrency Level:      16
Time taken for tests:   1.233 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      1983000 bytes
HTML transferred:       1738000 bytes
Requests per second:    811.05 [#/sec] (mean)
Time per request:       19.727 [ms] (mean)
Time per request:       1.233 [ms] (mean, across all concurrent requests)
Transfer rate:          1570.63 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1   0.9      1      18
Processing:     8   19   3.8     18      52
Waiting:        1   15   3.8     14      51
Total:          8   19   4.0     19      56

Percentage of the requests served within a certain time (ms)
  50%     19
  66%     20
  75%     20
  80%     21
  90%     22
  95%     25
  98%     27
  99%     29
 100%     56 (longest request)
```