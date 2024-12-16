import http from "k6/http";
import { sleep } from "k6";

export default function () {
    http.get("http://103.126.226.59");
    sleep(1);
}