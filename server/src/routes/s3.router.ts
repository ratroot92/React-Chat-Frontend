import express from 'express';
import s3Controller from '../controller/s3.controller';
import { ensureDir } from '../helpers';

const s3Router = express.Router();
s3Router.post(`/upload`, ensureDir(`${process.cwd()}/public/temp`), s3Controller.post);
s3Router.put(`/upload`, ensureDir(`${process.cwd()}/public/temp`), s3Controller.put);
s3Router.delete(`/upload`, s3Controller.delete);

export default s3Router;
