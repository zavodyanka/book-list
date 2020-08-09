import { ICategory } from './category';

export interface IBook {
    id: number;
    name: string;
    author: string;
    categories: ICategory[];
}