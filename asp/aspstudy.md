# ASP 공부용

## ASP 기초
- asp의 문장 마지막에는 세미콜론을 찍기 않는다.
- asp 구문의 처음과 끝에 스크립틀릿(<% %>)을 명시해줄것!

## 변수 선언
```
dim 변수이름
```
변수이름 앞에 dim을 입력후 사용

## 문자 출력
```
<% response.write("hello world!!!") %>
<%
= hello world!!! 
%>
```
두가지 방법으로 문자들을 출력을 할 수 있다.

## 조건문
### IF...Then
```
If 조건문 Then 실행구문
If 조건문 Then
　　실행구문 1
　　실행구문 2
End If
```
조건문을 확인 한 후에 실행 구문을 실행 시킨다.

### IF...Then...Else
```
If 조건문 1 Then
　　[실행구문 1]
[ElseIf 조건문 2 Then
　　[실행구문 2]] ...
[Else
　　[실행구문 n]]
End If
```
조건문이 참이면 해당 실행 구문을 실행한다. 조건이 맞지 않는다면 다른 조건을 확인하고 모든 조건이 아니면 else이 후의 구문을 실행한다.

### Select Case
```
Select Case 조건식
　　[Case 목록1
　　　　[실행구문-1]]
　　[Case 목록2
　　　　[실행구문-2]]

　　...

　　[Case Else
　　　　[실행구문-n]]
End Select
```
조건식을 비교하고 조건식에 나온 값을 해당 case 목록과 비교 후 존재하는 값의 실행구문을 실행한다. 모두 맞지 않는다면 case else의 실행구문을 사용한다.

## 루프
### For...Next
```
For 카운터 = 시작값 To 종료값 [Step 증가값]
　　실행구문
　　...
Next
```
우선 카운터 변수를 하나 설정한다. 이 변수를 기준으로 시작하여 증가값에 따라 변수에 증가한다. 증가한 변수가 종료값과 같아지면 반복을 끝낸다.

### For Each... Next
```
For Each 요소 In 그룹
　　실행구문
　　...
Next
```
```
ex)
<%
Dim Language(5)

Language(0) = "ASP"
Language(1) = "PHP"
Language(2) = "Visual Basic"
Language(3) = "Delphi"
Language(4) = "Power Builder"

For Each Item In Language
　　Response.Write Item & "<BR>"
Next
%>
```

### Do... Loop
```
Do While 조건문
　　실행구문
　　...
Loop
```
