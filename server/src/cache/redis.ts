import os from 'os';
import * as redis from 'redis';
import { createClient } from 'redis';

const networkInterfaces = os.networkInterfaces();

const ipv4Address = Object.values(networkInterfaces)
  .flat()
  .find((iface) => iface.family === 'IPv4' && iface.internal === false)?.address;

console.log('Machine IPv4 Address:', ipv4Address);
let redisOptions: redis.RedisClientOptions;
if (ipv4Address === '172.31.45.193') {
  redisOptions = {
    url: 'redis://localhost:6379',
    // password: 'ksby871FBLS*^@3',
    database: 0,
    disableOfflineQueue: false,
  };
} else {
  redisOptions = {
    url: 'redis://localhost:6379',
    password: 'ksby871FBLS*^@3',
    database: 0,
    disableOfflineQueue: false,
  };
}

const RedisCache = createClient(redisOptions);

(async () => {
  try {
    await RedisCache.connect();
    RedisCache.on('error', (err) => console.log('Redis Client Error', err));
    console.log('Redis SET', await RedisCache.set('CHAT_MESSAGES', '[]'));
    console.log('Redis GET', await RedisCache.get('CHAT_MESSAGES'));
  } catch (err) {
    console.log(err);
  }
})();

// await RedisCache.disconnect();
export default RedisCache;
