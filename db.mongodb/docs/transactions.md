In this section, we'll go through the code to create a transaction step by step. We start the transaction by using the session’s withTransaction() method. We then define the sequence of operations to perform inside the transactions, passing the session object to each operation in the transactions.

```js

// The startSession() method starts a client session, before the transaction is initiated.
const session = client.startSession()

try {
    // Begin a transaction with the WithTransaction() method on the session. The withTransaction() method starts the transaction, runs the callback, then commits or cancels the transaction.
    const transactionResults = await session.withTransaction(async () => {
        // Operations will go here
        const senderUpdate = await accounts.updateOne(
            { account_id: account_id_sender },
            { $inc: { balance: -transaction_amount } },
            { session }
        )

        const insertTransferResults = await transfers.insertOne({
            transfer_id: "TR21872187",
            amount: 100,
            from_account: account_id_sender,
            to_account: account_id_receiver,
        }, { session })
    })

    if (transactionResults) {
        console.log("Transaction completed successfully.")
    } else {
        console.log("Transaction failed.")
    }

} catch (err) {
    console.error(`Transaction aborted: ${err}`)
    process.exit(1)
} finally {
    await session.endSession()
    await client.close()
}
```

## References 
- [Creating MongoDB Transactions in Node.js Applications](https://learn.mongodb.com/learn/course/mongodb-crud-operations-in-nodejs/lesson-6-creating-mongodb-transactions-in-nodejs-applications/learn?client=customer&page=2)