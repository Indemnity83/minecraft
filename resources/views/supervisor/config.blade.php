[program:{{ $server->slug }}]
directory={{ $server->directory }}
command=java -Xmx{{ $server->java_xmx }}m -Xms{{ $server->java_xms }}m {{ $server->java_args }} -jar {{ $server->jar_file }} nogui {{ $server->jar_args }}
autorestart={{ $server->auto_restart }}
autostart={{ $server->auto_start }}
startretries={{ $server->start_retries }}
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
