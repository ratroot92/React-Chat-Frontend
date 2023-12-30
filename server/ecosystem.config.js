module.exports = {
  apps: [
    {
      name: 'server',
      script: 'dist/index.js',
      max_memory_restart: '300M',
      // instances: 'min',
      watch: false,
      out_file: './ec2.log',
      env: {
        NODE_ENV: 'development', // Example environment variable
      },
    },
  ],
};
