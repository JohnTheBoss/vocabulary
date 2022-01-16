import { Word } from "./word";

export interface Dictionary {
    id?: Number;
    name: String;
    knownLanguage: String;
    foreignLanguage: String;
    words?: Array<Word>;
}