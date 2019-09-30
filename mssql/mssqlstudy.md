# MSSQL 공부하기

## 1. 자료형

### 숫자형 데이터 타입 
| 데이터 형식 | 바이트 수| 숫자 범위 | 설명 |
|:---:|:---:|:---:|:---:|
|BIT |1 |0 또는 1 |Boolean형인 참, 거짓으로 사용 |
|INT| 4| -21억~+21억| 정수 |
|SMALLINT| 2| -32,768~32,767| 정수 |
|TINYINT| 1| 0~255| 양의정수 |
|BIGINT| 8| -263~263| 정수|
|DECIMAL(p,s)| 5~17| N/A| p는 자릿수, s는 소수점 자릿수 |
|NUMERIC| 5~17| N/A| DECIMAL과 같은 형식 |
|REAL| 4|| FLOAT[24]와 동일 |
|FLOAT[p]| 4~8|| p가 24 이하면 4바이트 25이상이면 8바이트 |
|MONEY| 8| -263~263| 화폐단위로 사용 |
|SMALLMONEY| 4| -21억~+21억| 화폐단위로 사용|

## 2. mssql select top
```
SELECT TOP '숫자' * form '테이블명';
```
해당 숫자 만큼의 데이터를 보여준다.

## 3. mssql select offset 절
```
SELECT * from '테이블명' ORDER BY '필드명' '숫자' rows;
```
숫자만큼 건너띄고 다음 부터 데이터를 보여준다.

```
SELECT * from '테이블명' ORDER BY '필드명' '숫자1' rows fetch next '숫자2' rows only;
```
숫자1 만큼 제외하고 숫자2만큼의 갯수의 데이터를 보여준다.

## 4. mssql distinct
```
SELECT DISTINCT * form '테이블명';
```
중복이 되는 부분을 통합하여 보여준다.

## 5. mssql 오름차순 / 내림차순
```
SELECT * FROM '테이블명'  
WHERE Name LIKE '키워드'  
ORDER BY '필드명' DESC;
```
필드명을 기준으로 내림 차순으로 정렬을 시킨다.

```
SELECT * FROM '테이블명'  
WHERE Name LIKE '키워드'  
ORDER BY '필드명' ASC ;
```
필드명을 기준으로 오름 차순으로 정렬을 시킨다.(작성을 안했을 시 기본값으로 오름차순으로 진행한다.)

## 6. mssql lock
```
SELECT * FROM '테이블명' with (nolock);
```
데이터를 읽을 때 공유 잠금이 유지 된다.
SELECT문이 실행 중인 동안에 같은 row 혹은 table에 insert나 update하는 작업은 block된다. 이렇게 되면 데이터베이스 성능이 현저히 떨어지게 되는데 이를 해결하기위해서 with (nolock)문을 추가하여 사용한다.

## 7. mssql isnull()
```
SELECT ISNULL(칼럼,칼럼이 NULL일경우 대체할 값)
FROM '테이블명'
```
컬럼이 null값일 경우 다른 값으로 대체할 수있는 기능

## 8. mssql 테이블 복사
```
SELECT * INTO [생성할 테이블명 ] FROM [원본 테이블명]
```
기본적으로 테이블과 동시에 데이터까지 복사하는 방법

```
SELECT [원하는 컬럼명] INTO [생성할 테이블명 ] FROM [원본 테이블명]
```
내가 원하는 컬럼만 복사하여 테이블을 만들때 간단하게 해결이 가능하다.

```
SELECT * INTO [생성할 테이블명 ] FROM [원본 테이블명] WHERE  1=2
```
테이블을 복사할때 테이블에서 데이터는 없이 테이블 구조만 복사할때는 'WHERE 1=2'와 같은 쿼리를 실행시키면 테이블의 구조만 복사할 수 있다.
