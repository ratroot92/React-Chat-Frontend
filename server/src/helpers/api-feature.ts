class ApiFeature {
  public query;
  public queryStr: any;

  constructor(query: any, queryStr: any) {
    this.query = query;
    this.queryStr = queryStr;
  }

  search() {
    const keyword = this.queryStr.keyword
      ? {
          name: {
            $regex: this.queryStr.keyword,
            $options: 'i',
          },
        }
      : {};
    this.query = this.query.find({ ...keyword });
    return this;
  }

  filter() {
    const queryCopy = { ...this.queryStr };
    // Remove Some Fields
    const removeableFields = ['keyword', 'page', 'limit'];
    removeableFields.forEach((key) => delete queryCopy[key]);
    // Filter for price etc
    let queryStr = JSON.stringify(queryCopy);
    queryStr = queryStr.replace(/\b(gt|gte|lt|lte)\b/g, (key: string) => `$${key}`);
    this.query = this.query.find(JSON.parse(queryStr));

    return this;
  }

  pagination(resultPerPage: number) {
    const currentPage = Number(this.queryStr.page) || 1;
    const skip = resultPerPage * (currentPage - 1);
    this.query = this.query.limit(resultPerPage).skip(skip);
    return this;
  }
  reversePagination(resultPerPage: number, totalRecords: number) {
    const currentPage = Number(this.queryStr.page) || 1;
    const totalPages = Math.ceil(totalRecords / resultPerPage);

    // Calculate skip and limit for reverse pagination
    const skip = Math.max(0, totalRecords - currentPage * resultPerPage);

    // Adjust limit for the last page
    let limit = resultPerPage;
    if (currentPage === totalPages) {
      limit = totalRecords % resultPerPage || resultPerPage;
    }

    this.query = this.query.limit(limit).skip(skip);
    return this;
  }
}

export default ApiFeature;
