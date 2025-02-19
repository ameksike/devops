### Definitions
- **Aggregation**: Collection and summary of data
- **Stage**: One of the built-in methods that can be completed on the data, but does not permanently alter it
- **Aggregation pipeline**: A series of stages completed on the data in order
- **Aggregation operators** construct expressions for use in the aggregation pipeline stages. Operator expressions are similar to functions that take arguments. Which component(s) of an aggregation pipeline do documents pass through for processing?

### Structure of an Aggregation Pipeline

```js
db.collection.aggregate([
    {
        $stage1: {
            { expression1 },
            { expression2 }...
        },
        $stage2: {
            { expression1 }...
        }
    }
])
```

### Match
The $match stage filters for documents that match specified conditions. Here's the code for $match:
```js
{
    $match: {
        "field_name": "value"
    }
}
```

### Group
The $group stage groups documents by a group key:
```js
{
    $group: {
        _id: <expression>, // Group key
        <field>: { <accumulator> : <expression> }
    }
}
```

The following aggregation pipeline finds the documents with a field named "state" that matches a value "CA" and then groups those documents by the group key "$city" and shows the total number of zip codes in the state of California. $match and $group in an Aggregation Pipeline:
```js
db.zips.aggregate([
    {   
        $match: { 
            state: "CA"
        }
    },
    {
        $group: {
            _id: "$city",
            totalZips: { $count : { } }
        }
    }
])
```

### Sort & Limit
The following aggregation pipeline sorts the documents in descending order, so the documents with the greatest pop value appear first, and limits the output to only the first five documents after sorting.
- We use 1 to represent ascending order, and -1 to represent descending order.
```js
db.zips.aggregate([
    {
        $sort: {
            pop: -1
        }
    },
    {
        $limit:  5
    }
])
```

### Projections 
The $project stage specifies the fields of the output documents. 1 means that the field should be included, and 0 means that the field should be supressed. The field can also be assigned a new value.
```js
db.zips.aggregate([
    {
        $project: {
            state:1, 
            zip:1,
            population:"$pop",
            _id:0
        }
    }
])
```

### Set 
The $set stage creates new fields or changes the value of existing fields, and then outputs the documents with the new fields.
```js
db.zips.aggregate([
    {
        $set: {
            place: {
                $concat:["$city",",","$state"]
            },
            pop:10000
        }
    }
])
```

### Count
The $count stage creates a new document, with the number of documents at that stage in the aggregation pipeline assigned to the specified field name.
```js
db.zips.aggregate([
    {
        $count: "total_zips"
    }
])
```

### Out 
- Writes the documents that are returned by an aggregation pipeline into a collection 
- it must be the last stage 
- Creates a new collection if it does not already exist 
- If the collection exists, it replaces the existing collection with new data.

## Node.Js Example
Aggregation gives you a way to transform data from your collection by passing documents from one stage to another. These stages can consist of operators that transform or organize your data in a specific way. In the following example, we build an aggregation pipeline that uses $match, $sort, and $project, and that will find checking accounts with a balance of greater than or equal to $1,500. Then, we sort the results by the balance in descending order and return only the account_id, account_type, balance, and a new computed field named gbp_balance, which stands for Great British Pounds (GBP) balance.

```js
const client = new MongoClient(uri)
const dbname = "bank";
const collection_name = "accounts";
const accountsCollection = client.db(dbname).collection(collection_name);

const pipeline = [
  // Stage 1: $match - filter the documents (checking, balance >= 1500)
  { $match: { account_type: "checking", balance: { $gte: 1500 } } },

  // Stage 2: Calculate average balance and total balance
  {
    $group: {
      _id: "$account_type",
      total_balance: { $sum: "$balance" },
      avg_balance: { $avg: "$balance" },
    },
  },
  
  // Stage 4: $sort - sorts the documents in descending order (balance)
  { $sort: { balance: -1 } },

  // Stage 5: $project - project only the requested fields and one computed field (account_type, account_id, balance, gbp_balance)
  {
    $project: {
      _id: 0,
      account_id: 1,
      account_type: 1,
      balance: 1,
      // GBP stands for Great British Pound
      gbp_balance: { $divide: ["$balance", 1.3] },
    },
  },
]

const main = async () => {
  try {
    await client.connect()
    console.log(`Connected to the database 🌍. \nFull connection string: ${safeURI}`)
    let result = await accountsCollection.aggregate(pipeline)
    for await (const doc of result) {
      console.log(doc)
    }
  } catch (err) {
    console.error(`Error connecting to the database: ${err}`)
  } finally {
    await client.close()
  }
}

main()
```

## Conclusions
Aggregation operations process data records and return computed results. When working with data in MongoDB, you may have to quickly run complex operations that involve multiple stages to gather metrics for your project. Generating reports and displaying useful metadata are just two major use cases where MongoDB aggregation operations can be incredibly useful, powerful, and flexible.

## References 
- [Introduction to MongoDB Aggregation](https://learn.mongodb.com/learn/course/mongodb-aggregation/lesson-1-introduction-to-mongodb-aggregation/learn?client=customer&page=2)
- [Using MongoDB Aggregation Stages with Node.js: $match and $group](https://learn.mongodb.com/learn/course/mongodb-aggregation-with-nodejs/lesson-2-using-mongodb-aggregation-stages-with-nodejs-match-and-group/learn?client=customer&page=2)