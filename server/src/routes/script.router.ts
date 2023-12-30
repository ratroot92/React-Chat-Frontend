import express from 'express';
import scriptController from '../controller/script.controller';

const scriptRouter = express.Router();
scriptRouter.post(`/exec`, scriptController.executeScript);

export default scriptRouter;
