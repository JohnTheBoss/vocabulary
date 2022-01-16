import { BaseResponse } from "./baseResponse";
import { TokenModel } from "./tokenModel";
import { UserModel } from "./userModel";

export interface LoginResponse extends BaseResponse {
    user: UserModel;
    token: TokenModel;
}