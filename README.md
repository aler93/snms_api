### SportNinaMiniStats API
#### Setup
 * docker-compose up yields two containers with MariaDB and Redis
 * .env file must be configured with credentials to access DB and Redis
 * php artisan migrate --seed (Seeder adds players, Temporary Stats table commented)

#### Database:
 * **Players:** Contains players data
 * **stats_temp:** Saves players statistics and inform if the data has been already processed
 * **stats:** Contains players statistics, should only accept one name (statistic name) per player, PHP should read stats_temp and update the information on the stats table

#### Endpoints:
 * [POST] /stat - Adds an statistic directly to stats_temp table, no Redis/cache used
 * [GET] /stat - Read and returns the statistics in stats table
 * [POST] /stat/cached - Adds an statistic to Redis server (waiting to be updated to stats table, could also be used to add to stats_temp)
 * [GET] /stat/cached/{player_id} - Search for one specific player still in cache (Debug purpose)
 * [GET] /stat/cached - returns all data still in cache (Debug purpose)

#### Commands
 * **ProcessStats:** Read all data in stats_temp where computed=false and adds to stats, creating a new record or updating existing (may be able to optimize using Upsert)
 * **ProcessCachedStats:** Read all data in cache and adds to stats, creating a new record or updating existing (may be able to optimize using Upsert)

#### Schedule:
 * Runs both commands every minute
