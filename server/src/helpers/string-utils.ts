class StringUtils {
  public stringToBoolean(str: string) {
    if (str && /^(true|yes|1|on)$/i.test(str)) {
      return true;
    } else if (str && /^(false|no|0|off)$/i.test(str)) {
      return false;
    } else {
      return false;
    }
  }
}

export default new StringUtils();
