#!/usr/bin/env bash
docker container exec -it db psql -h 127.0.0.1 -d ranked_choice -U rc_admin -W
