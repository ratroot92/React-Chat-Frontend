/* eslint-disable no-empty */
/* eslint-disable @typescript-eslint/no-empty-function */
/* eslint-disable security/detect-non-literal-fs-filename */
import util from 'util';
import fs from 'fs';
const fileUtils = {
  existAsync: util.promisify(fs.exists),
  writeAsync: util.promisify(fs.write),
  writeFileAsync: util.promisify(fs.writeFile),
  mkdirAsync: util.promisify(fs.mkdir),
  unlinkAsync: util.promisify(fs.unlink),
  readAsync: util.promisify(fs.read),
  readFileAsync: util.promisify(fs.readFile),

  autoUnlink: async (filePath: string): Promise<void> => {
    try {
      const fileExists = fs.existsSync(filePath);
      if (fileExists) {
        await fs.promises.unlink(filePath);
      } else {
      }
    } catch (err) {
      console.error('An error occurred:', err);
    }
  },
};

export { fileUtils };
