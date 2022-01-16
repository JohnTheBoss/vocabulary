import { BaseResponse } from "./baseResponse";
import { Dictionary } from "./dictionary";

export interface EnrolledDictionaryResponse extends BaseResponse {
    enrolled?: Dictionary[];
}