### CRUD 
Insert operations: 
```js
// Insert a Single Document: 
db.grades.insertOne({
  student_id: 654321,
  class_id: 550,
  products: [
    {
      type: "exam",
      score: 90,
    },
    {
      type: "homework",
      score: 59,
    }
  ],
})

// Insert Multiple Documents:
db.grades.insertMany([
  {
    student_id: 546789,
    products: [],
    class_id: 551,
  },
  {
    student_id: 777777,
    products: [
      {
        type: "exam",
        score: 83,
      },
      {
        type: "quiz",
        score: 59,
      },
    ],
    class_id: 550,
  },
  {
    student_id: 223344,
    products: [],
    class_id: 551,
  },
])
```

Find Operations: 
```js
// Find a Document with Equality:
db.zips.find({ _id: ObjectId("5c8eccc1caa187d17ca6ed16") })

// Find a Document by Using the $in Operator:
db.zips.find({ city: { $in: ["PHOENIX", "CHICAGO"] } })

// Finding Documents by Using Comparison Operators:
db.sales.find({ "items.price": { $gt: 50}})
db.sales.find({ "customer.age": { $lte: 65}})

// Find Documents with an Array That Contains a Specified Value
// In the following example, This query will return all documents that contain InvestmentFund as a scalar value and as an array element within the "products" field.
db.accounts.find({ products: "InvestmentFund"})

// Find a Document by Using the $elemMatch Operator
db.sales.find({
  items: {
    $elemMatch: { name: "laptop", price: { $gt: 800 }, quantity: { $gte: 1 } },
  },
})
```

Logical Operators:
```js
// Find a Document by Using Implicit $and
db.routes.find({ "airline.name": "Southwest Airlines", stops: { $gte: 1 } })

// Find a Document by Using the $or Operator
db.routes.find({
  $or: [{ dst_airport: "SEA" }, { src_airport: "SEA" }],
})

// Find a Document by Using the $and Operator
db.routes.find({
  $and: [
    { $or: [{ dst_airport: "SEA" }, { src_airport: "SEA" }] },
    { $or: [{ "airline.name": "American Airlines" }, { airplane: 320 }] },
  ]
})
```

Update Operations: 
```js
// replace one document based on a filter
db.books.replaceOne(
  {
    _id: ObjectId("6282afeb441a74a98dbbec4e"),
  },
  {
    title: "Data Science Fundamentals for Python and MongoDB",
    isbn: "1484235967",
    publishedDate: new Date("2018-5-10"),
    thumbnailUrl:
      "https://m.media-amazon.com/images/I/71opmUBc2wL._AC_UY218_.jpg",
    authors: ["David Paper"],
    categories: ["Data Science"],
  }
)

// Updating MongoDB Documents
// The $set operator replaces the value of a field with the specified value, as shown in the following code:
db.podcasts.updateOne(
  {
    _id: ObjectId("5e8f8f8f8f8f8f8f8f8f8f8"),
  },

  {
    $set: {
      subscribers: 98562,
    },
  }
)

// The upsert option creates a new document if no documents match the filtered criteria. Here's an example:
db.podcasts.updateOne(
  { title: "The Developer Hub" },
  { $set: { topics: ["databases", "MongoDB"] } },
  { upsert: true }
)

// The $push operator adds a new value to the hosts array field. Here's an example:
db.podcasts.updateOne(
  { _id: ObjectId("5e8f8f8f8f8f8f8f8f8f8f8") },
  { $push: { hosts: "Nic Raboy" } }
)

// The findAndModify() method is used to find and replace a single document in MongoDB. It accepts a filter document, a replacement document, and an optional options object. 
// When the upsert option is set to true, a new document will be inserted if one does not already exist. For existing documents, the upsert option will cause the document to be updated.
// The following code shows an example:
db.podcasts.findAndModify({
  query: { _id: ObjectId("6261a92dfee1ff300dc80bf1") },
  update: { $inc: { subscribers: 1 } },
  new: true,
})

// To update multiple documents, use the updateMany() method. This method accepts a filter document, an update document, and an optional options object. The following code shows an example:
db.books.updateMany(
  { publishedDate: { $lt: new Date("2019-01-01") } },
  { $set: { status: "LEGACY" } }
)
```

Delete Operations: 
```js
// Delete One Document
db.podcasts.deleteOne({ _id: Objectid("6282c9862acb966e76bbf20a") })

// Delete Many Documents
db.podcasts.deleteMany({ category: "crime" })

// This query will delete all documents that contain an airline name of Air Berlin.
db.routes.deleteMany({ "airline.name": "Air Berlin"})
```

Sorting Results: 
```js
// Return data on all music companies, sorted alphabetically from A to Z.
// value 1 for ascending and -1 for descending order.
db.companies.find({ category_code: "music" }).sort({ name: 1 });

// Return data on all music companies, sorted alphabetically from A to Z. Ensure consistent sort order
db.companies.find({ category_code: "music" }).sort({ name: 1, _id: 1 });
```

Limiting Results: 
```js
db.companies
  .find({ category_code: "music" })
  .sort({ number_of_employees: -1, _id: 1 })
  .limit(3);
```
Projection Operations: 
```js
// Include Fields
// Return all restaurant inspections - business name, result fields only
// value 1 for inclusion and -1 for exclusion statement.
db.inspections.find(
  { sector: "Restaurant - 818" },
  { business_name: 1, result: 1, _id: 0 }
)

// Exclude Fields
// // Return all inspections with result of "Pass" or "Warning" - exclude date and zip code
db.inspections.find(
  { result: { $in: ["Pass", "Warning"] } },
  { date: 0, "address.zip": 0 }
)
```

Count Documents: 
```js
// Count number of docs in trip collection
db.trips.countDocuments()

// Count number of trips over 120 minutes by subscribers
db.trips.countDocuments({ tripduration: { $gt: 120 }, usertype: "Subscriber" })
```

## References 
- [Learners journey](https://learn.mongodb.com/pages/learners-journey)
- [Usage examples](https://www.mongodb.com/docs/drivers/node/current/usage-examples/)
    - [Deleting Documents in Node.js Applications](https://learn.mongodb.com/learn/course/mongodb-crud-operations-in-nodejs/lesson-5-deleting-documents-in-nodejs-applications/learn?client=customer&page=2)
    - [Inserting a Document in Node.js Applications](https://learn.mongodb.com/learn/course/mongodb-crud-operations-in-nodejs/lesson-2-inserting-a-document-in-nodejs-applications/learn?client=customer&page=2)
    - [Updating Documents in Node.js Applications](https://learn.mongodb.com/learn/course/mongodb-crud-operations-in-nodejs/lesson-4-updating-documents-in-nodejs-applications/learn?client=customer&page=2)
- [Query Your Data](https://www.mongodb.com/docs/compass/current/query/filter/)
- [Connect to a MongoDB Database Using Node.js](https://www.mongodb.com/developer/languages/javascript/node-connect-mongodb/)
- [MongoDB and Node.js 3.3.2 Tutorial - CRUD Operations](https://www.mongodb.com/developer/languages/javascript/node-crud-tutorial-3-3-2/)
- [How to Integrate MongoDB Into Your Next.js App](https://www.mongodb.com/developer/languages/javascript/nextjs-with-mongodb/)