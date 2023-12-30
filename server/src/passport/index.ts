/* eslint-disable @typescript-eslint/ban-ts-comment */
import { Request } from 'express';
import passport from 'passport';
import { Strategy as JwtStrategy } from 'passport-jwt';
import { Strategy as LocalStrategy } from 'passport-local';
import { IUserDoc, UserModel } from '../models/User';

const clientSecretKey = process.env.JWT_CLIENT_SECRET;
const adminScecretKey = process.env.JWT_ADMIN_SECRET;

// const { ExtractJwt } = require("passport-jwt")
const cookieExtractorFunc = (request: Request): string | null => {
  let accessToken = null;
  if (request && request.cookies) {
    accessToken = request.cookies['access_token'];
  }
  return accessToken;
};

const extractJWT = (request: Request): string | null => {
  let accessToken: string | null = null;
  if (request && request.headers.authorization) {
    const authorizationHeader = request.headers.authorization as string;
    const [authType, authValue] = authorizationHeader.split(' ');
    // @ts-ignore
    if (authType.toLowerCase() === 'bearer') {
      // @ts-ignore

      accessToken = authValue;
    }
  }
  return accessToken;
};

passport.use(
  'client-jwt',
  new JwtStrategy(
    {
      // jwtFromRequest: ExtractJwt.fromHeader("authorization"),
      jwtFromRequest: extractJWT,
      secretOrKey: clientSecretKey || 'clientSecret',
    },
    ({ sub: user }, done) => {
      try {
        return done(null, user);
      } catch (err) {
        return done(err, false);
      }
    }
  )
);
passport.use(
  'admin-jwt',
  new JwtStrategy(
    {
      // jwtFromRequest: ExtractJwt.fromHeader("authorization"),
      // jwtFromRequest: cookieExtractorFunc,
      jwtFromRequest: extractJWT,
      secretOrKey: adminScecretKey || 'adminSecret',
    },
    ({ sub: user }, done) => {
      try {
        if (user.role === 'admin') {
          return done(null, user);
        } else {
          return done(null, false);
        }
      } catch (err) {
        return done(err, false);
      }
    }
  )
);

passport.use(
  'client-login',
  new LocalStrategy(
    {
      usernameField: 'email',
      passwordField: 'password',
    },
    async (email, password, done) => {
      try {
        //@ts-ignore
        let user: IUserDoc | null = await UserModel.findOne({ email: { $regex: new RegExp(email, 'i') }, role: 'user', isAdminApproved: true }).select('+password');
        if (!user) {
          return done(null, false, { message: 'User not found.' });
        }
        // @ts-ignore
        const isMatch: boolean = await user.matchPassword(password);
        if (!isMatch) {
          return done(null, false, { message: 'Incorrect password.' });
        }
        // @ts-ignore
        user = user.maskPassword();
        return done(null, user);
      } catch (err) {
        return done(err, false);
      }
    }
  )
);

passport.use(
  'admin-login',
  new LocalStrategy(
    {
      usernameField: 'email',
      passwordField: 'password',
    },
    async (email, password, done) => {
      try {
        //@ts-ignore
        let user: IUserDoc | null = await UserModel.findOne({ email: { $regex: new RegExp(email, 'i') }, role: 'admin' }).select('+password');
        if (!user) {
          return done(null, false, { message: 'User not found.' });
        }
        // @ts-ignore
        const isMatch: boolean = await user.matchPassword(password);
        if (!isMatch) return done(null, false, { message: 'Incorrect password.' });
        // @ts-ignore
        user = user.maskPassword();
        return done(null, user);
      } catch (err) {
        return done(err, false);
      }
    }
  )
);

export const ProtectAdmin = passport.authenticate('admin-jwt', { session: false });
export const ProtectClient = passport.authenticate('client-jwt', { session: false });

export default passport;
