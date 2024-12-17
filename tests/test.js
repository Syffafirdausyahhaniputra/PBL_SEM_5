import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  stages: [
    { duration: '20s', target: 200 }, 
    { duration: '20s', target: 300 }, 
    { duration: '20s', target: 400 }, 
  ],
};

export default function () {
  const res = http.get('http://103.126.226.59'); // Target domain
  check(res, { 'status was 200': (r) => r.status == 200 });
  sleep(1);
}