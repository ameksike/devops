// connect to the database
db = db.getSiblingDB("storage");
// clean the collection animal
db.animal.drop();
// seed the collection animal
db.animal.insertMany([
    {
        "id": 1,
        "name": "Lion",
        "type": "wild"
    },
    {
        "id": 2,
        "name": "Cow",
        "type": "domestic"
    },
    {
        "id": 3,
        "name": "Tiger",
        "type": "wild"
    },
]);