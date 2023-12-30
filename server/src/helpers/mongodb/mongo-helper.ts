import mongoose from 'mongoose';

import { AppLogger } from '../app-logger';

export class MongoDb {
  private dbHost: string;
  private dbName: string;
  private dbPort: string | number;
  private dbOptions: any;

  private db: any;
  constructor() {
    this.dbHost = process.env.MONGO_HOST || '127.0.0.1';
    this.dbName = process.env.MONGO_DB_NAME || 'ish-dev';
    this.dbPort = process.env.MONGO_PORT || 27017;
    this.dbOptions = {
      useNewUrlParser: true,
      useUnifiedTopology: true,
    };
  }

  public async connect(): Promise<any> {
    try {
      this.db = await mongoose.connect(`mongodb://${this.dbHost}:${this.dbPort}/${this.dbName}`, { ...this.dbOptions });
      AppLogger.info(process.env.NODE_ENV || 'development', `mongodb://${this.dbHost}:${this.dbPort}/${this.dbName}`);
    } catch (err) {
      AppLogger.error(process.env.NODE_ENV || 'development', `Failed to connect mongo db. using uri `);
    }
  }

  public async drop(): Promise<any> {
    try {
      await mongoose.connection.db.dropDatabase();
      AppLogger.info(process.env.NODE_ENV || 'development', `MongoDb dropped.`);
    } catch (err) {
      AppLogger.error(process.env.NODE_ENV || 'development', `Failed to drop mongo db.`);
    }
  }
}
