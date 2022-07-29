# UNIwise backend opgave:

to install app please run
```
make run
```
the project runs on url 
[http://localhost:8500](http://localhost:8500)

phpmyadmin runs on url 
[http://localhost:9997](http://localhost:9997)
## 2 types of filters applied:
### api-platform:

the best known symfony api framework [api-platform](https://api-platform.com/)
to visit the docs page of our apis please visit 
[http://localhost:8500/api](http://localhost:8500/api) then you can try the endpoint
`api/cars` with it's filters

### custom made filter:

the endpoint url is
[http://localhost:8500/cars](http://localhost:8500/cars)

you can filter with

1- color: takes `string`
2- gasEconomy: boolean *takes* `0` or `1`
3- model: it's an array the key is `gt`, `gte`, `lt`, `lte`,or `eq` and the value is `year`

you can sort with any Car Entity Property:
sort is an array the key is the property and the value is `asc` or `dsc`


example of url `http://localhost:8500/cars?sort[createdAt]=asc&&sort[model]=dsc&&gasEconomy=0&&model[gt]=1970&&model[lte]=2000&&color=PowderBlue`
